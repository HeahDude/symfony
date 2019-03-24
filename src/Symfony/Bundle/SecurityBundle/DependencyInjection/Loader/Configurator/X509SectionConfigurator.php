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
class X509SectionConfigurator extends FirewallSectionConfigurator
{
    use Traits\FirewallSubSectionTrait;

    private $type = 'x509';

    public function x509UserProvider(string $name)
    {
        $this->configureFirewall('provider', $name);

        return $this;
    }

    public function x509User(string $user)
    {
        $this->configureFirewall('user', $user);

        return $this;
    }

    public function x509Credentials(string $credentials)
    {
        $this->configureFirewall('credentials', $credentials);

        return $this;
    }
}
