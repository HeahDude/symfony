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

/**
 * internal
 */
class HttpClientSectionConfigurator extends AbstractExtensionSectionConfigurator
{
    use CanBeEnabledTrait;
    use Traits\HttpClientOptionsTrait;

    public const NAMESPACE = 'framework';
    public const SECTION = 'http_client';

    private $clients;

    public function __construct(AbstractExtensionConfigurator $extension)
    {
        parent::__construct($extension);

        $this->clients = new \ArrayObject();
    }

    public function __destruct()
    {
        $this
            ->set('default_options', $this->options)
            ->set('clients', $this->clients->getArrayCopy())
        ;
        if (null !== $this->maxHostConnections) {
            $this->set('max_host_connections', $this->maxHostConnections);
        }

        parent::__destruct();
    }

    public function client(string $name)
    {
        return new class($this, $this->clients, $name) extends HttpClientSectionConfigurator {
            private $clients;
            private $name;

            public function __construct(HttpClientSectionConfigurator $extension, \ArrayObject $clients, string $name)
            {
                parent::__construct($extension);

                $this->name = $name;
                $this->clients = $clients;
            }

            public function __destruct()
            {
                $this->clients[$this->name]['default_options'] = $this->options;

                if (null !== $this->maxHostConnections) {
                    $this->clients[$this->name]['max_host_connections'] = $this->maxHostConnections;
                }
            }

            // allow chaining
            public function client(string $name): HttpClientSectionConfigurator
            {
                return $this->extension->client($name);
            }
        };
    }
}
