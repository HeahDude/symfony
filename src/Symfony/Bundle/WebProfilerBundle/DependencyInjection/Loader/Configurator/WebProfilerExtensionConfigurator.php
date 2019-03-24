<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Bundle\WebProfilerBundle\DependencyInjection\Loader\Configurator;

use Symfony\Component\DependencyInjection\Loader\Configurator\AbstractExtensionConfigurator;

final class WebProfilerExtensionConfigurator extends AbstractExtensionConfigurator
{
    public const NAMESPACE = 'web_profiler';

    public function toolbar(bool $enable = true)
    {
        return $this->set('toolbar', $enable);
    }

    public function interceptRedirects(bool $enable = true)
    {
        return $this->set('intercept_redirects', $enable);
    }

    public function excludedAjaxPaths(string $expression)
    {
        return $this->set('excluded_ajax_paths', $expression);
    }
}
