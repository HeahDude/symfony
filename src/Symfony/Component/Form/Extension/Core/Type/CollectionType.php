<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Form\Extension\Core\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CollectionList\Factory\CachingFactoryDecorator;
use Symfony\Component\Form\CollectionList\Factory\DefaultCollectionListFactory;
use Symfony\Component\Form\CollectionList\Factory\CollectionListFactoryInterface;
use Symfony\Component\Form\CollectionList\Factory\PropertyAccessDecorator;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Form\Extension\Core\EventListener\ResizeCollectionListListener;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CollectionType extends AbstractType
{
    /**
     * The factory to create the collection.
     *
     * @var CollectionListFactoryInterface
     */
    private $collectionListFactory;

    public function __construct(CollectionListFactoryInterface $collectionListFactory = null)
    {
        $this->collectionListFactory = $collectionListFactory ?: new CachingFactoryDecorator(
            new PropertyAccessDecorator(
                new DefaultCollectionListFactory()
            )
        );
    }
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['allow_add'] && $options['prototype']) {
            $prototype = $builder->create($options['prototype_name'], $options['entry_type'], $options['prototype_options']);
            $builder->setAttribute('prototype', $prototype->getForm());
        }

        $resizeListener = new ResizeCollectionListListener(
            $options['entry_type_map'],
            $options['entry_index'],
            $options['entry_options'],
            $options['allow_add'],
            $options['allow_delete'],
            $options['delete_empty']
        );

        $builder->addEventSubscriber($resizeListener);
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars = array_replace($view->vars, array(
            'allow_add' => $options['allow_add'],
            'allow_delete' => $options['allow_delete'],
        ));

        if ($form->getConfig()->hasAttribute('prototype')) {
            $view->vars['prototype'] = $form->getConfig()->getAttribute('prototype')->createView($view);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        if ($form->getConfig()->hasAttribute('prototype') && $view->vars['prototype']->vars['multipart']) {
            $view->vars['multipart'] = true;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        // BC default "entry_type_map" option to "entry_type" value. To be remove in 4.0.
        $entryTypeMap = function (Options $options) {
            if (null === $entryType = $options['entry_type']) {
                return __NAMESPACE__.'\TextType';
            } else {
                @trigger_error('The "entry_type" option is deprecated since version 3.1 and will be removed in 4.0. You should use "entry_type_map" option instead.', E_USER_DEPRECATED);
            }

            return $entryType;
        };

        $entryOptionsNormalizer = function (Options $options, $entryOptions) {
            if (is_array($entryOptions)) {
                $entryOptions['block_name'] = 'entry';

                return $entryOptions;
            }

            // Else it's a callable, just wrap it.
            return function ($entry) use ($entryOptions) {
                $dynamicOptions = call_user_func($entryOptions, $entry);

                if (!is_array($dynamicOptions)) {
                    throw new UnexpectedTypeException($dynamicOptions, 'array');
                }

                $dynamicOptions['block_name'] = 'entry';

                return $dynamicOptions;
            };
        };

        $prototypeOptionsNormalizer = function (Options $options, $value) {
            $entryOptions = is_array($options['entry_options'])
                ? $options['entry_options']
                // Use the callable with prototype data option
                : call_user_func(
                    $options['entry_options'],
                    // For BC
                    $options['prototype_data'] ?: (isset($value['data'])
                        ? $value['data'] : null)
                );

            if (null !== $options['prototype_data']) {
                @trigger_error('The "prototype_data" option is deprecated since version 3.1 and will be removed in 4.0. You should use "prototype_options" option instead.', E_USER_DEPRECATED);

                // Let 'prototype_data' option override `$entryOptions['data']` for BC
                $prototypeOptions['data'] = $options['prototype_data'];
            }

            // Default to 'entry_options' option
            $prototypeOptions = array_replace($entryOptions, $value);

            return array_replace(array(
                // Use the collection required state
                'required' => $options['required'],
                'label' => $options['prototype_name'].'label__',
            ), $prototypeOptions);
        };

        $resolver->setDefaults(array(
            'entry_type' => null, // deprecated
            'entry_type_map' => $entryTypeMap, // set default to {@link \Symfony\Component\Form\Extension\Core\Type\TextType} in 4.0
            'entry_index' => null,
            'entry_options' => array(),
            'prototype' => true,
            'prototype_data' => null, // deprecated
            'prototype_name' => '__name__',
            'prototype_options' => array(),
            'allow_add' => false,
            'allow_delete' => false,
            'delete_empty' => false,
            'collection_loader' => null,
        ));

        $resolver->setNormalizer('entry_options', $entryOptionsNormalizer);
        $resolver->setNormalizer('prototype_options', $prototypeOptionsNormalizer);

        $resolver->setAllowedTypes('entry_type', array('null', 'string', 'Symfony\Component\Form\FormTypeInterface'));
        $resolver->setAllowedTypes('entry_type_map', array('string', 'callable', 'Symfony\Component\Form\FormTypeInterface'));
        $resolver->setAllowedTypes('entry_index', array('null', 'callable', 'string', 'Symfony\Component\PropertyAccess\PropertyPath'));
        $resolver->setAllowedTypes('entry_options', array('array', 'callable'));
        $resolver->setAllowedTypes('prototype', 'bool');
        $resolver->setAllowedTypes('prototype_name', 'string');
        $resolver->setAllowedTypes('prototype_options', 'array');
        $resolver->setAllowedTypes('allow_add', 'bool');
        $resolver->setAllowedTypes('allow_delete', 'bool');
        $resolver->setAllowedTypes('delete_empty', 'bool');
        $resolver->setAllowedTypes('collection_loader', array('null', 'string', 'Symfony\Component\Form\CollectionList\Loader\CollectionListLoader'));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'collection';
    }
}
