<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Bundle\SecurityBundle\DependencyInjection\Loader\Configurator;

use Symfony\Component\DependencyInjection\Loader\Configurator\AbstractExtensionSectionConfigurator;

/**
 * @internal
 */
class AccessControlSectionConfigurator extends AbstractExtensionSectionConfigurator
{
    public const NAMESPACE = 'security';
    public const SECTION = 'access_control';

    private $accessControl;

    public function __construct(SecurityExtensionConfigurator $extension, \ArrayObject $accessControl)
    {
        parent::__construct($extension);

        $this->accessControl = $accessControl;
    }

    public function __destruct()
    {
        $this->accessControl[] = $this->config;
    }

    /**
     * Use the decoded URL format.
     *
     * @param string $pattern
     *
     * @return $this
     */
    public function path(string $pattern)
    {
        return $this->set('path', $pattern);
    }

    public function ip(string ...$ips)
    {
        return $this->set('ips', $ips);
    }

    public function methods(string ...$methods)
    {
        return $this->set('methods', $methods);
    }

    public function requiresChannel(string $channel)
    {
        return $this->set('requires_channel', $channel);
    }

    public function roles(string ...$roles)
    {
        return $this->set('roles', $roles);
    }

    public function allowIf(string $condition)
    {
        return $this->set('allow_if', $condition);
    }

    public function accessControl(): self
    {
        return $this->extension->accessControl();
    }
}
