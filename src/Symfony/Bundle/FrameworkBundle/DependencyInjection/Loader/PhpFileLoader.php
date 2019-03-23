<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Bundle\FrameworkBundle\DependencyInjection\Loader;

use Symfony\Bundle\FrameworkBundle\DependencyInjection\Loader\Configurator\KernelContainerConfigurator;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader as BasePhpFileLoader;

/**
 * @author Jules Pietri <jules@heahprod.com>
 */
class PhpFileLoader extends BasePhpFileLoader
{
    protected function getConfigurator(string $path, string $file): ContainerConfigurator
    {
        return new KernelContainerConfigurator($this->container, $this, $this->instanceof, $path, $file);
    }
}
