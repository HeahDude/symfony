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

/**
 * @internal
 */
class HttpBasicSectionConfigurator extends FirewallSectionConfigurator
{
    use Traits\FirewallSubSectionTrait;

    private $type = 'http_basic';

    public function httpBasicRealm(string $realm)
    {
        $this->configureFirewall('realm', $realm);

        return $this;
    }

    public function httpBasicUserProvider(string $name)
    {
        $this->configureFirewall('provider', $name);

        return $this;
    }
}
