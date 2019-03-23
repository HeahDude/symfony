<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Bundle\FrameworkBundle\DependencyInjection\Loader\Configurator;

use Doctrine\Common\Annotations\Annotation;
use Symfony\Bundle\TwigBundle\DependencyInjection\Loader\Configurator\TwigExtensionConfigurator;
use Symfony\Component\Asset\Package;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Workflow\Workflow;

class KernelContainerConfigurator extends ContainerConfigurator
{
    final public function framework(): FrameworkExtensionConfigurator
    {
        return new FrameworkExtensionConfigurator($this);
    }

    final public function router(): RouterSectionConfigurator
    {
        return new RouterSectionConfigurator($this->framework());
    }

    final public function session(): SessionSectionConfigurator
    {
        return new SessionSectionConfigurator($this->framework());
    }

    final public function profiler(): ProfilerSectionConfigurator
    {
        return new ProfilerSectionConfigurator($this->framework());
    }

    final public function workflow(string $name): WorkflowSectionConfigurator
    {
        if (!class_exists(Workflow::class)) {
            throw new \LogicException('The "workflows" section is not configurable. Are you sure to use the Workflow component? Try "composer require symfony/workflow".');
        }

        return new WorkflowSectionConfigurator($this->framework(), $name, false);
    }

    final public function stateMachine(string $name): WorkflowSectionConfigurator
    {
        if (!class_exists(Workflow::class)) {
            throw new \LogicException('The "workflows" section is not configurable. Are you sure to use the Workflow component? Try "composer require symfony/workflow".');
        }

        return new WorkflowSectionConfigurator($this->framework(), $name);
    }

    final public function assets(): AssetsSectionConfigurator
    {
        if (!class_exists(Package::class)) {
            throw new \LogicException('The "assets" section is not configurable. Are you sure to use the Assets component? Try "composer require symfony/assets".');
        }

        return new AssetsSectionConfigurator($this->framework());
    }

    final public function translation(): TranslatorSectionConfigurator
    {
        if (!class_exists(Translator::class)) {
            throw new \LogicException('The "translator" section is not configurable. Are you sure to use the Translation component? Try "composer require symfony/translation".');
        }

        return new TranslatorSectionConfigurator($this->framework());
    }

    final public function validation(): ValidationSectionConfigurator
    {
        if (!class_exists(Validation::class)) {
            throw new \LogicException('The "validation" section is not configurable. Are you sure to use the Validator component? Try "composer require symfony/validator".');
        }

        return new ValidationSectionConfigurator($this->framework());
    }

    final public function annotations(): AnnotationsSectionConfigurator
    {
        if (!class_exists(Annotation::class)) {
            throw new \LogicException('The "annotations" section is not configurable. Are you sure to use the required dependencies? Try "composer require sensio/framework-extra-bundle".');
        }

        return new AnnotationsSectionConfigurator($this->framework());
    }

    final public function twig(): TwigExtensionConfigurator
    {
        if (!class_exists(TwigExtensionConfigurator::class)) {
            throw new \LogicException('The "twig" extension is not configurable. Are you sure to use the TwigBundle? Try "composer require symfony/twig-bundle".');
        }

        return new TwigExtensionConfigurator($this);
    }
}
