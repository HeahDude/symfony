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
use Symfony\Bundle\DebugBundle\DebugBundle;
use Symfony\Bundle\DebugBundle\DependencyInjection\Loader\Configurator\DebugExtensionConfigurator;
use Symfony\Bundle\TwigBundle\DependencyInjection\Loader\Configurator\TwigExtensionConfigurator;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Component\Asset\Package;
use Symfony\Component\Debug\Debug;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Lock\Lock;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyInfo\PropertyInfoExtractorInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Validator\Validation;
use Symfony\Component\WebLink\HttpHeaderSerializer;
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

    final public function assets(bool $enable = true): AssetsSectionConfigurator
    {
        if (!class_exists(Package::class)) {
            throw new \LogicException('The "assets" section is not configurable. Are you sure to use the Assets component? Try "composer require symfony/assets".');
        }

        return (new AssetsSectionConfigurator($this->framework()))->enable($enable);
    }

    final public function translation(bool $enable = true): TranslatorSectionConfigurator
    {
        if (!class_exists(Translator::class)) {
            throw new \LogicException('The "translator" section is not configurable. Are you sure to use the Translation component? Try "composer require symfony/translation".');
        }

        return (new TranslatorSectionConfigurator($this->framework()))->enable($enable);
    }

    final public function validation(bool $enable = true): ValidationSectionConfigurator
    {
        if (!class_exists(Validation::class)) {
            throw new \LogicException('The "validation" section is not configurable. Are you sure to use the Validator component? Try "composer require symfony/validator".');
        }

        return (new ValidationSectionConfigurator($this->framework()))->enable($enable);
    }

    final public function annotations(bool $enable = true): AnnotationsSectionConfigurator
    {
        if (!class_exists(Annotation::class)) {
            throw new \LogicException('The "annotations" section is not configurable. Are you sure to use the required dependencies? Try "composer require sensio/framework-extra-bundle".');
        }

        return (new AnnotationsSectionConfigurator($this->framework()))->enable($enable);
    }

    final public function serializer(bool $enable = true): AnnotationsSectionConfigurator
    {
        if (!class_exists(Serializer::class)) {
            throw new \LogicException('The "serializer" section is not configurable. Are you sure to use the Serializer component? Try "composer require symfony/serializer".');
        }

        return (new AnnotationsSectionConfigurator($this->framework()))->enable($enable);
    }

    final public function propertyAccess(): PropertyAccessSectionConfigurator
    {
        if (!class_exists(PropertyAccess::class)) {
            throw new \LogicException('The "property_access" section is not configurable. Are you sure to use the PropertyAccess component? Try "composer require symfony/property-access".');
        }

        return new PropertyAccessSectionConfigurator($this->framework());
    }

    final public function propertyInfo($enable = true): FrameworkExtensionConfigurator
    {
        if (!interface_exists(PropertyInfoExtractorInterface::class)) {
            throw new \LogicException('The "property_info" section is not configurable. Are you sure to use the PropertyAccess component? Try "composer require symfony/property-info".');
        }

        return $this->extension('property_info', ['enabled' => $enable]);
    }

    final public function cache(): CacheSectionConfigurator
    {
        return new CacheSectionConfigurator($this->framework());
    }

    final public function phpErrors(): PhpErrorsSectionConfigurator
    {
        return new PhpErrorsSectionConfigurator($this->framework());
    }

    final public function lock(bool $enable = true): LockSectionConfigurator
    {
        if (!class_exists(Lock::class)) {
            throw new \LogicException('The "lock" section is not configurable. Are you sure to use the Lock component? Try "composer require symfony/lock".');
        }

        return (new LockSectionConfigurator($this->framework()))->enable($enable);
    }

    final public function webLink($enable = true): FrameworkExtensionConfigurator
    {
        if (!class_exists(HttpHeaderSerializer::class)) {
            throw new \LogicException('The "web_link" section is not configurable. Are you sure to use the WebLink component? Try "composer require symfony/web-link".');
        }

        return $this->extension('web_link', ['enabled' => $enable]);
    }

    final public function messenger($enable = true): MessengerSectionConfigurator
    {
        if (!interface_exists(MessageBusInterface::class)) {
            throw new \LogicException('The "messenger" section is not configurable. Are you sure to use the Messenger component? Try "composer require symfony/messenger".');
        }

        return (new MessengerSectionConfigurator($this->framework()))->enable($enable);
    }
    final public function httpClient($enable = true): HttpClientSectionConfigurator
    {
        if (!interface_exists(HttpClient::class)) {
            throw new \LogicException('The "http_client" section is not configurable. Are you sure to use the HttpClient component? Try "composer require symfony/http-client".');
        }

        return (new HttpClientSectionConfigurator($this->framework()))->enable($enable);
    }

    final public function debug(): DebugExtensionConfigurator
    {
        if (!class_exists(Debug::class)) {
            throw new \LogicException('The "debug" extension is not configurable. Are you sure to use the DebugBundle? Try "composer require symfony/debug-bundle".');
        }
        if (!method_exists(DebugBundle::class, 'getExtensionConfigurator')) {
            throw new \LogicException('The "debug" extension is not configurable using fluent methods. Try upgrading "composer update symfony/debug-bundle".');
        }

        return DebugBundle::getExtensionConfigurator($this);
    }

    final public function twig(): TwigExtensionConfigurator
    {
        if (!class_exists(TwigBundle::class)) {
            throw new \LogicException('The "twig" extension is not configurable. Are you sure to use the TwigBundle? Try "composer require symfony/twig-bundle".');
        }
        if (!method_exists(TwigBundle::class, 'getExtensionConfigurator')) {
            throw new \LogicException('The "twig" extension is not configurable using fluent methods. Try upgrading "composer update symfony/twig-bundle".');
        }

        return TwigBundle::getExtensionConfigurator($this);
    }
}
