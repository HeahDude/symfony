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

final class TranslatorSectionConfigurator extends AbstractExtensionSectionConfigurator
{
    use CanBeEnabledTrait;

    public const NAMESPACE = 'framework';
    public const SECTION = 'translator';

    /**
     * @param string|array $fallbacks One or many fallbacks
     *
     * @return $this
     */
    public function fallbacks($fallbacks)
    {
        return $this->set('fallbacks', $fallbacks);
    }

    public function logging(bool $enable = true)
    {
        return $this->set('logging', $enable);
    }

    public function formatter(string $serviceId)
    {
        return $this->set('formatter', $serviceId);
    }

    public function defaultPath(string $path)
    {
        return $this->set('default_path', $path);
    }

    public function paths(array $paths)
    {
        return $this->set('paths', $paths);
    }
}
