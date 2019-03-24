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
class GuardSectionConfigurator extends FirewallSectionConfigurator
{
    use Traits\FirewallSubSectionTrait;

    private $type = 'guard';

    private $authenticators = [];

    public function __destruct()
    {
        $this->configureFirewall('authenticators', $this->authenticators);

        parent::__destruct();
    }

    public function guardProvider(string $name)
    {
        $this->configureFirewall('provider', $name);

        return $this;
    }

    public function guardEntryPoint(string $serviceId)
    {
        $this->configureFirewall('entry_point', $serviceId);

        return $this;
    }

    public function guardAuthenticator(string $serviceId)
    {
        $this->authenticators[] = $serviceId;

        return $this;
    }
}
