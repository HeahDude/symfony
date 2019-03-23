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

use Symfony\Bundle\TwigBundle\DependencyInjection\Loader\Configurator\TwigExtensionConfigurator;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
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

    final public function twig(): TwigExtensionConfigurator
    {
        if (!class_exists(TwigExtensionConfigurator::class)) {
            throw new \LogicException('The "twig" extension is not configurable. Are you sure to use the TwigBundle? Try "composer require symfony/twig-bundle".');
        }

        return new TwigExtensionConfigurator($this);
    }
}
