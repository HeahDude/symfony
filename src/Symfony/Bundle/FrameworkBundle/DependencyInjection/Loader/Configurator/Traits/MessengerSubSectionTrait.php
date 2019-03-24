<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Bundle\FrameworkBundle\DependencyInjection\Loader\Configurator\Traits;

use Symfony\Bundle\FrameworkBundle\DependencyInjection\Loader\Configurator\BusSectionConfigurator;
use Symfony\Bundle\FrameworkBundle\DependencyInjection\Loader\Configurator\MessengerSectionConfigurator;
use Symfony\Bundle\FrameworkBundle\DependencyInjection\Loader\Configurator\TransportSectionConfigurator;

/**
 * @internal
 */
trait MessengerSubSectionTrait
{
    public function route(string $messageClass, $senders, bool $sendAndHandle = false): MessengerSectionConfigurator
    {
        return $this->extension->route($messageClass, $senders, $sendAndHandle);
    }

    public function serializer(string $id): MessengerSectionConfigurator
    {
        return $this->extension->serializer($id);
    }

    public function serializerFormat(string $format): MessengerSectionConfigurator
    {
        return $this->extension->serializerFormat($format);
    }

    public function serializerContext(string $name, $context = null): MessengerSectionConfigurator
    {
        return $this->extension->serializerContext($name, $context);
    }

    public function transport(string $name, string $dsn, array $options = null): TransportSectionConfigurator
    {
        return $this->extension->transport($name, $dsn, $options);
    }

    public function defaultBus(string $name): MessengerSectionConfigurator
    {
        return $this->extension->defaultBus($name);
    }

    public function bus(string $name): BusSectionConfigurator
    {
        return $this->extension->bus($name);
    }
}
