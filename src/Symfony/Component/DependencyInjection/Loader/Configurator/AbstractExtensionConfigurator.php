<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

/**
 * @author Jules Pietri <jules@heahprod.com>
 */
abstract class AbstractExtensionConfigurator
{
    public const NAMESPACE = 'abstract';

    protected $config = [];
    protected $configurator;

    public function __construct(ContainerConfigurator $configurator)
    {
        $this->configurator = $configurator;
    }

    public function __destruct()
    {
        $this->configurator->extension(static::NAMESPACE, $this->config);
    }

    /**
     * Defines a configuration value for an extension.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return $this
     */
    final protected function set(string $key, $value)
    {
        $this->config[$key] = $value;

        return $this;
    }
}
