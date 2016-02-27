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
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Extension\Core\EventListener\ResizeFormListener;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CollectionType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['allow_add'] && $options['prototype']) {
            $prototype = $builder->create($options['prototype_name'], $options['entry_type'], $options['prototype_options']);
            $builder->setAttribute('prototype', $prototype->getForm());
        }

        $resizeListener = new ResizeFormListener(
            $options['entry_type'],
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
        $entryOptionsNormalizer = function (Options $options, $entryOptions) {
            $entryOptions['block_name'] = 'entry';

            return $entryOptions;
        };

        $prototypeOptionsNormalizer = function (Options $options, $prototypeOptions) {
            // Default to 'entry_options' option
            $prototypeOptions = array_replace($options['entry_options'], $prototypeOptions);

            if (null !== $options['prototype_data']) {
                @trigger_error('The "prototype_data" option is deprecated since version 3.1 and will be removed in 4.0. You should use "prototype_options" option instead.', E_USER_DEPRECATED);

                // Let 'prototype_data' option override `$entryOptions['data']` for BC
                $prototypeOptions['data'] = $options['prototype_data'];
            }

            return array_replace(array(
                // Use the collection required state
                'required' => $options['required'],
                'label' => $options['prototype_name'].'label__',
            ), $prototypeOptions);
        };

        $resolver->setDefaults(array(
            'entry_type' => __NAMESPACE__.'\TextType',
            'entry_options' => array(),
            'prototype' => true,
            'prototype_data' => null, // deprecated
            'prototype_name' => '__name__',
            'prototype_options' => array(),
            'allow_add' => false,
            'allow_delete' => false,
            'delete_empty' => false,
        ));

        $resolver->setNormalizer('entry_options', $entryOptionsNormalizer);
        $resolver->setNormalizer('prototype_options', $prototypeOptionsNormalizer);

        $resolver->setAllowedTypes('entry_type', array('string', 'Symfony\Component\Form\FormTypeInterface'));
        $resolver->setAllowedTypes('entry_options', 'array');
        $resolver->setAllowedTypes('prototype', 'bool');
        $resolver->setAllowedTypes('prototype_name', 'string');
        $resolver->setAllowedTypes('prototype_options', 'array');
        $resolver->setAllowedTypes('allow_add', 'bool');
        $resolver->setAllowedTypes('allow_delete', 'bool');
        $resolver->setAllowedTypes('delete_empty', 'bool');
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'collection';
    }
}
