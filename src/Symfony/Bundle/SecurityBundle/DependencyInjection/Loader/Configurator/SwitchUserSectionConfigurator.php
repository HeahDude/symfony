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

final class SwitchUserSectionConfigurator extends FirewallSectionConfigurator
{
    use Traits\FirewallSubSectionTrait;

    private $type = 'switch_user';

    public function switchUserProvider(string $name)
    {
        $this->configureFirewall('provider', $name);

        return $this;
    }

    public function switchUserParameter(string $name)
    {
        $this->configureFirewall('parameter', $name);

        return $this;
    }

    public function switchUserRole(string $role)
    {
        $this->configureFirewall('role', $role);

        return $this;
    }
}
