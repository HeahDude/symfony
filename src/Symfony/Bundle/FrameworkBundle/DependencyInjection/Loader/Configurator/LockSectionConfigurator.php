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

use Symfony\Component\DependencyInjection\Loader\Configurator\AbstractExtensionSectionConfigurator;
use Symfony\Component\DependencyInjection\Loader\Configurator\Traits\CanBeEnabledTrait;

final class LockSectionConfigurator extends AbstractExtensionSectionConfigurator
{
    use CanBeEnabledTrait;

    public const NAMESPACE = 'framework';
    public const SECTION = 'lock';

    private $resources = [];

    public function __destruct()
    {
        $this->set('resources', $this->resources);

        parent::__destruct();
    }

    /**
     * @param string|string[] $stores
     *
     * @return $this
     */
    public function store($stores)
    {
        $this->resources['default'] = $stores;

        return $this;
    }

    /**
     * @param string          $name
     * @param string|string[] $stores
     *
     * @return $this
     */
    public function resource(string $name, $stores)
    {
        $this->resources[$name] = $stores;

        return $this;
    }
}
