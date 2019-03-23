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

final class RouterSectionConfigurator extends AbstractExtensionSectionConfigurator
{
    public const NAMESPACE = 'framework';
    public const SECTION = 'router';

    public function resource(string $resource, string $type = null)
    {
        if ($type) {
            $this->set('type', $type);
        }

        return $this->set('resource', $resource);
    }

    public function httpPort(int $httpPort)
    {
        return $this->set('http_port', $httpPort);
    }

    public function httpsPort(int $httpsPort)
    {
        return $this->set('https_port', $httpsPort);
    }

    public function strictRequirements(bool $strict = true)
    {
        return $this->set('strict_requirements', $strict);
    }

    public function utf8(bool $utf8 = true)
    {
        return $this->set('utf8', $utf8);
    }
}
