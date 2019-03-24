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

use Symfony\Component\DependencyInjection\Loader\Configurator\AbstractExtensionConfigurator;
use Symfony\Component\DependencyInjection\Loader\Configurator\AbstractExtensionSectionConfigurator;
use Symfony\Component\DependencyInjection\Loader\Configurator\Traits\CanBeEnabledTrait;

class MessengerSectionConfigurator extends AbstractExtensionSectionConfigurator
{
    use CanBeEnabledTrait;

    public const NAMESPACE = 'framework';
    public const SECTION = 'messenger';

    private $routing = [];
    private $serializer = [];
    private $transports;
    private $buses;

    public function __construct(AbstractExtensionConfigurator $extension)
    {
        parent::__construct($extension);

        $this->transports = new \ArrayObject();
        $this->buses = new \ArrayObject();
    }

    public function __destruct()
    {
        $this
            ->set('routing', $this->routing)
            ->set('serializer', $this->serializer)
            ->set('transports', $this->transports->getArrayCopy())
            ->set('buses', $this->buses->getArrayCopy())
        ;

        parent::__destruct();
    }

    /**
     * @param string|array $senders One or more sender ids.
     *
     * @return MessengerSectionConfigurator
     */
    public function route(string $messageClass, $senders, bool $sendAndHandle = false)
    {
        $this->routing[$messageClass] = ['senders' => $senders, 'send_and_handle' => $sendAndHandle];

        return $this;
    }

    /**
     * @return MessengerSectionConfigurator
     */
    public function serializer(string $id)
    {
        $this->serializer['id'] = $id;

        return $this;
    }

    /**
     * @return MessengerSectionConfigurator
     */
    public function serializerFormat(string $format)
    {
        $this->serializer['format'] = $format;

        return $this;
    }

    /**
     * @param mixed $context
     *
     * @return MessengerSectionConfigurator
     */
    public function serializerContext(string $name, $context = null)
    {
        $this->serializer['context'][$name] = $context;

        return $this;
    }

    public function transport(string $name, string $dsn, array $options = null): TransportSectionConfigurator
    {
        $this->transports[$name]['dsn'] = $dsn;
        if ($options) {
            $this->transports[$name]['options'] = $options;
        }

        return new TransportSectionConfigurator($this, $this->transports, $name);
    }

    public function defaultBus(string $name)
    {
        return $this->set('default_bus', $name);
    }

    public function bus(string $name): BusSectionConfigurator
    {
        return new BusSectionConfigurator($this, $this->buses, $name);
    }
}

/**
 * @internal
 */
class TransportSectionConfigurator extends MessengerSectionConfigurator
{
    use Traits\MessengerSubSectionTrait;

    private $name;
    private $transports;
    private $retryStrategy;

    public function __construct(MessengerSectionConfigurator $extension, \ArrayObject $transports, string $name)
    {
        parent::__construct($extension);

        $this->name = $name;
        $this->transports = $transports;
    }

    public function __destruct()
    {
        $this->transports[$this->name]['retry_strategy'] = $this->retryStrategy;
    }

    public function retryStrategyService(string $id)
    {
        $this->retryStrategy['service'] = $id;

        return $this;
    }

    public function maxRetries(int $max)
    {
        $this->retryStrategy['max_retries'] = $max;

        return $this;
    }

    public function retryDelay(int $delay)
    {
        $this->retryStrategy['delay'] = $delay;

        return $this;
    }

    public function retryMaxDelay(int $maxDelay)
    {
        $this->retryStrategy['max_delay'] = $maxDelay;

        return $this;
    }

    public function retryMultiplier(float $multiplier)
    {
        $this->retryStrategy['multiplier'] = $multiplier;

        return $this;
    }
}

/**
 * @internal
 */
class BusSectionConfigurator extends MessengerSectionConfigurator
{
    use Traits\MessengerSubSectionTrait;

    private $name;
    private $buses;
    private $defaultMiddleware = true;
    private $middlewares = [];

    public function __construct(MessengerSectionConfigurator $extension, \ArrayObject $buses, string $name)
    {
        parent::__construct($extension);

        $this->name = $name;
        $this->buses = $buses;
    }

    public function __destruct()
    {
        $this->buses[$this->name] = [
            'default_middleware' => $this->defaultMiddleware,
            'middleware' => $this->middlewares,
        ];
    }

    /**
     * @param bool|string $default
     *
     * @return $this
     */
    public function defaultMiddleware($default)
    {
        $this->defaultMiddleware = $default;

        return $this;
    }

    public function middleware(string $id, array $arguments = [])
    {
        $middleware['id'] = $id;
        if ($arguments) {
            $middleware['arguments'] = $arguments;
        }

        $this->middlewares[] = $middleware;

        return $this;
    }
}
