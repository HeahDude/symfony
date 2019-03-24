<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Bundle\SecurityBundle\DependencyInjection\Loader\Configurator\Traits;

use Symfony\Bundle\SecurityBundle\DependencyInjection\Loader\Configurator\FirewallSectionConfigurator;
use Symfony\Bundle\SecurityBundle\DependencyInjection\Loader\Configurator\FormLoginLdapSectionConfigurator;
use Symfony\Bundle\SecurityBundle\DependencyInjection\Loader\Configurator\FormLoginSectionConfigurator;
use Symfony\Bundle\SecurityBundle\DependencyInjection\Loader\Configurator\GuardSectionConfigurator;
use Symfony\Bundle\SecurityBundle\DependencyInjection\Loader\Configurator\HttpBasicLdapSectionConfigurator;
use Symfony\Bundle\SecurityBundle\DependencyInjection\Loader\Configurator\HttpBasicSectionConfigurator;
use Symfony\Bundle\SecurityBundle\DependencyInjection\Loader\Configurator\JsonLoginLdapSectionConfigurator;
use Symfony\Bundle\SecurityBundle\DependencyInjection\Loader\Configurator\JsonLoginSectionConfigurator;
use Symfony\Bundle\SecurityBundle\DependencyInjection\Loader\Configurator\LogoutSectionConfigurator;
use Symfony\Bundle\SecurityBundle\DependencyInjection\Loader\Configurator\RememberMeSectionConfigurator;
use Symfony\Bundle\SecurityBundle\DependencyInjection\Loader\Configurator\SecurityExtensionConfigurator;
use Symfony\Bundle\SecurityBundle\DependencyInjection\Loader\Configurator\SwitchUserSectionConfigurator;
use Symfony\Bundle\SecurityBundle\DependencyInjection\Loader\Configurator\X509SectionConfigurator;

/**
 * @internal
 */
trait FirewallSubSectionTrait
{
    private $firewall;

    public function __construct(SecurityExtensionConfigurator $extension, FirewallSectionConfigurator $firewall, \ArrayObject $firewalls, string $name, bool $enable = true)
    {
        parent::__construct($extension, $firewalls, $name, $enable);

        $this->firewall = $firewall;

        if ($enable) {
            $this->firewalls[$name][$this->type] = [];
        }
    }

    public function __destruct()
    {
        // noop, changes are made in the parent already
    }

    public function pattern(string $expression): FirewallSectionConfigurator
    {
        return $this->firewall->pattern($expression);
    }

    public function host(string $expression): FirewallSectionConfigurator
    {
        return $this->firewall->host($expression);
    }

    public function methods(array $methods): FirewallSectionConfigurator
    {
        return $this->firewall->methods($methods);
    }

    public function security(bool $enable = true): FirewallSectionConfigurator
    {
        return $this->firewall->security($enable);
    }

    public function userChecker(string $serviceId): FirewallSectionConfigurator
    {
        return $this->firewall->userChecker($serviceId);
    }

    public function requestMatcher(string $serviceId): FirewallSectionConfigurator
    {
        return $this->firewall->requestMatcher($serviceId);
    }

    public function accessDeniedUrl(string $url): FirewallSectionConfigurator
    {
        return $this->firewall->accessDeniedUrl($url);
    }

    public function accessDeniedHandler(string $serviceId): FirewallSectionConfigurator
    {
        return $this->firewall->accessDeniedHandler($serviceId);
    }

    public function entryPoint(string $serviceId): FirewallSectionConfigurator
    {
        return $this->firewall->entryPoint($serviceId);
    }

    public function usersProvider(string $name): FirewallSectionConfigurator
    {
        return $this->firewall->usersProvider($name);
    }

    public function stateless(bool $stateless = true): FirewallSectionConfigurator
    {
        return $this->firewall->stateless($stateless);
    }

    public function context(string $key): FirewallSectionConfigurator
    {
        return $this->firewall->context($key);
    }

    public function logoutOnUserChange(bool $logout = true): FirewallSectionConfigurator
    {
        return $this->firewall->logoutOnUserChange($logout);
    }

    public function anonymous(bool $enable = true, string $secret = null)
    {
        return $this->firewall->anonymous($enable, $secret);
    }

    public function switchUser(bool $enable = true): SwitchUserSectionConfigurator
    {
        return $this->firewall->switchUser($enable);
    }

    public function rememberMe(bool $enable = true, string $secret = null): RememberMeSectionConfigurator
    {
        return $this->firewall->rememberMe($enable, $secret);
    }

    public function httpBasic(bool $enable = true): HttpBasicSectionConfigurator
    {
        return $this->firewall->httpBasic($enable);
    }

    public function httpBasicLdap(bool $enable = true): HttpBasicLdapSectionConfigurator
    {
        return $this->firewall->httpBasicLdap($enable);
    }

    public function formLogin(bool $enable = true): FormLoginSectionConfigurator
    {
        return $this->firewall->formLogin($enable);
    }

    public function formLoginLdap(bool $enable = true): FormLoginLdapSectionConfigurator
    {
        return $this->firewall->formLoginLdap($enable);
    }

    public function jsonLogin(bool $enable = true): JsonLoginSectionConfigurator
    {
        return $this->firewall->jsonLogin($enable);
    }

    public function jsonLoginLdap(bool $enable = true): JsonLoginLdapSectionConfigurator
    {
        return $this->firewall->jsonLoginLdap($enable);
    }

    public function x509(bool $enable = true): X509SectionConfigurator
    {
        return $this->firewall->x509($enable);
    }

    public function guard(): GuardSectionConfigurator
    {
        return $this->firewall->guard();
    }

    public function logout(bool $enable = true): LogoutSectionConfigurator
    {
        return $this->firewall->logout($enable);
    }

    public function firewall(string $name, string $path = '/'): FirewallSectionConfigurator
    {
        return $this->firewall->firewall($name, $path);
    }

    final protected function configureFirewall($key, $value)
    {
        $this->firewalls[$this->name][$this->type][$key] = $value;
    }
}
