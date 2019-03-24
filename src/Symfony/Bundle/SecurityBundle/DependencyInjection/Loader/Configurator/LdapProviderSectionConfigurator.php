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

final class LdapProviderSectionConfigurator extends AbstractExtensionSectionConfigurator
{
    public const NAMESPACE = 'security';
    public const SECTION = 'providers';

    private $providers;
    private $name;

    public function __construct(SecurityExtensionConfigurator $extension, \ArrayObject $providers, string $name)
    {
        parent::__construct($extension);

        $this->providers = $providers;
        $this->name = $name;
    }

    public function __destruct()
    {
        $this->providers[$this->name] = ['ldap' => $this->config];
    }

    public function service(string $name)
    {
        return $this->set('service', $name);
    }

    public function baseDn(string $dn)
    {
        return $this->set('base_dn', $dn);
    }

    public function searchDn(string $dn)
    {
        return $this->set('search_dn', $dn);
    }

    public function searchPassword(string $searchPassword)
    {
        return $this->set('search_password', $searchPassword);
    }

    public function defaultRoles(array $roles)
    {
        return $this->set('default_roles', $roles);
    }

    public function uidKey(string $key)
    {
        return $this->set('uid_key', $key);
    }

    public function filter(string $filter)
    {
        return $this->set('filter', $filter);
    }

    public function password_attribute(string $attribute)
    {
        return $this->set('password_attribute', $attribute);
    }
}
