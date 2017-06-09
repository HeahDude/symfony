<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Form;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
abstract class AbstractTypeExtension implements FormTypeExtensionInterface
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
    }

    /**
     * {@inheritdoc}
     */
    public static function getExtendedTypes()
    {
        if (!method_exists(static::class, __METHOD__)) {
            @trigger_error(sprintf('%s::getExtendedType() is deprecated since version 3.3. Implement the static method "getExtendedTypes()" to provide types class name instead. It will be part of %s in 4.0.', static::class, FormTypeExtensionInterface::class), E_USER_DEPRECATED);

            $extension = new static();

            yield $extension->getExtendedType();
        }
    }
}
