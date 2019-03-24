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

final class JsonLoginLdapSectionConfigurator extends JsonLoginSectionConfigurator
{
    private $type = 'json_login_ldap';

    public function jsonLoginLdapService(string $id)
    {
        $this->configureFirewall('service', $id);

        return $this;
    }

    public function jsonLoginLdapDnString(string $string)
    {
        $this->configureFirewall('dn_string', $string);

        return $this;
    }

    public function jsonLoginLdapQueryString(string $string)
    {
        $this->configureFirewall('query_string', $string);

        return $this;
    }
}
