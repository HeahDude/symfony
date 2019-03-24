<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Bundle\WebProfilerBundle;

use Symfony\Bundle\WebProfilerBundle\DependencyInjection\Loader\Configurator\WebProfilerExtensionConfigurator;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @author Fabien Potencier <fabien@symfony.com>
 */
class WebProfilerBundle extends Bundle
{
    public function boot()
    {
        if ('prod' === $this->container->getParameter('kernel.environment')) {
            @trigger_error('Using WebProfilerBundle in production is not supported and puts your project at risk, disable it.', E_USER_WARNING);
        }
    }

    public static function getExtensionConfigurator(ContainerConfigurator $containerConfigurator)
    {
        return new WebProfilerExtensionConfigurator($containerConfigurator);
    }
}
