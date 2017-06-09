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
interface FormTypeExtensionInterface
{
    /**
     * Builds the form.
     *
     * This method is called after the extended type has built the form to
     * further modify it.
     *
     * @see FormTypeInterface::buildForm()
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     */
    public function buildForm(FormBuilderInterface $builder, array $options);

    /**
     * Builds the view.
     *
     * This method is called after the extended type has built the view to
     * further modify it.
     *
     * @see FormTypeInterface::buildView()
     *
     * @param FormView      $view    The view
     * @param FormInterface $form    The form
     * @param array         $options The options
     */
    public function buildView(FormView $view, FormInterface $form, array $options);

    /**
     * Finishes the view.
     *
     * This method is called after the extended type has finished the view to
     * further modify it.
     *
     * @see FormTypeInterface::finishView()
     *
     * @param FormView      $view    The view
     * @param FormInterface $form    The form
     * @param array         $options The options
     */
    public function finishView(FormView $view, FormInterface $form, array $options);

    /**
     * Configures the options for this type.
     *
     * @param OptionsResolver $resolver The resolver for the options
     */
    public function configureOptions(OptionsResolver $resolver);

    /**
     * @deprecated since 3.4, will be removed in 4.0.
     *             Implement the static getExtendedTypes() to provide types class name instead.
     *
     * Returns the name of the type being extended.
     *
     * @return string The name of the type being extended
     */
    public function getExtendedType();

    /**
     * Uncomment in 4.0.
     *
     * Provides the class of the types being extended.
     *
     * @return iterable The full qualified class name of the types being extended
     */
    //public static function getExtendedTypes();
}
