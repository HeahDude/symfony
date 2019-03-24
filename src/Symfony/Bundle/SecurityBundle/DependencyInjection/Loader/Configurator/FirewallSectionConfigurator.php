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
class FirewallSectionConfigurator extends AbstractExtensionSectionConfigurator
{
    public const NAMESPACE = 'security';
    public const SECTION = 'firewalls';

    protected $firewalls;
    protected $name;

    public function __construct(SecurityExtensionConfigurator $extension, \ArrayObject $firewalls, string $name, string $pattern)
    {
        parent::__construct($extension);

        $this->firewalls = $firewalls;
        $this->name = $name;

        $this->set('pattern', $pattern);
    }

    public function __destruct()
    {
        $this->firewalls[$this->name] = array_merge($this->firewalls[$this->name] ?? [], $this->config);
    }

    public function host(string $expression)
    {
        return $this->set('host', $expression);
    }

    public function methods(array $methods)
    {
        return $this->set('methods', $methods);
    }

    public function security(bool $enable = true)
    {
        return $this->set('security', $enable);
    }

    public function userChecker(string $serviceId)
    {
        return $this->set('user_checker', $serviceId);
    }

    public function requestMatcher(string $serviceId)
    {
        return $this->set('request_matcher', $serviceId);
    }

    /**
     * Cannot be chained to the main config.
     *
     * @param string $url For this firewall
     *
     * @return FirewallSectionConfigurator
     */
    public function accessDeniedUrl(string $url)
    {
        return $this->set('access_denied_url', $url);
    }

    public function accessDeniedHandler(string $serviceId)
    {
        return $this->set('access_denied_handler', $serviceId);
    }

    public function entryPoint(string $serviceId)
    {
        return $this->set('entry_point', $serviceId);
    }

    public function usersProvider(string $name)
    {
        return $this->set('provider', $name);
    }

    public function stateless(bool $stateless = true)
    {
        return $this->set('stateless', $stateless);
    }

    public function context(string $key)
    {
        return $this->set('context', $key);
    }

    public function logoutOnUserChange(bool $logout = true)
    {
        return $this->set('logout_on_user_change', $logout);
    }

    public function anonymous(bool $enable = true, string $secret = null)
    {
        if (!$enable) {
            unset($this->config['anonymous']);

            return $this;
        }

        return $this->set('anonymous', $secret ? ['secret' => $secret] : []);
    }

    public function switchUser(bool $enable = true): SwitchUserSectionConfigurator
    {
        $switchUser = new SwitchUserSectionConfigurator($this->extension, $this, $this->firewalls, $this->name, $enable);
        $switchUser->firewalls = $this->firewalls;

        return $switchUser;
    }

    public function rememberMe(bool $enable = true, string $secret = null): RememberMeSectionConfigurator
    {
        $rememberMe = new RememberMeSectionConfigurator($this->extension, $this, $this->firewalls, $this->name, $enable, $secret);
        $rememberMe->firewalls = $this->firewalls;

        return $rememberMe;
    }

    public function httpBasic(bool $enable = true): HttpBasicSectionConfigurator
    {
        $httpBasic = new HttpBasicSectionConfigurator($this->extension, $this, $this->firewalls, $this->name, $enable);
        $httpBasic->firewalls = $this->firewalls;

        return $httpBasic;
    }

    public function httpBasicLdap(bool $enable = true): HttpBasicLdapSectionConfigurator
    {
        $httpBasic = new HttpBasicLdapSectionConfigurator($this->extension, $this, $this->firewalls, $this->name, $enable);
        $httpBasic->firewalls = $this->firewalls;

        return $httpBasic;
    }

    public function formLogin(bool $enable = true): FormLoginSectionConfigurator
    {
        $formLogin = new FormLoginSectionConfigurator($this->extension, $this, $this->firewalls, $this->name, $enable);
        $formLogin->firewalls = $this->firewalls;

        return $formLogin;
    }

    public function formLoginLdap(bool $enable = true): FormLoginLdapSectionConfigurator
    {
        $formLogin = new FormLoginLdapSectionConfigurator($this->extension, $this, $this->firewalls, $this->name, $enable);
        $formLogin->firewalls = $this->firewalls;

        return $formLogin;
    }

    public function jsonLogin(bool $enable = true): JsonLoginSectionConfigurator
    {
        $jsonLogin = new JsonLoginSectionConfigurator($this->extension, $this, $this->firewalls, $this->name, $enable);
        $jsonLogin->firewalls = $this->firewalls;

        return $jsonLogin;
    }

    public function jsonLoginLdap(bool $enable = true): JsonLoginLdapSectionConfigurator
    {
        $jsonLogin = new JsonLoginLdapSectionConfigurator($this->extension, $this, $this->firewalls, $this->name, $enable);
        $jsonLogin->firewalls = $this->firewalls;

        return $jsonLogin;
    }

    public function x509(bool $enable = true): X509SectionConfigurator
    {
        $jsonLogin = new X509SectionConfigurator($this->extension, $this, $this->firewalls, $this->name, $enable);
        $jsonLogin->firewalls = $this->firewalls;

        return $jsonLogin;
    }

    public function guard(): GuardSectionConfigurator
    {
        $guard = new GuardSectionConfigurator($this->extension, $this, $this->firewalls, $this->name);
        $guard->firewalls = $this->firewalls;

        return $guard;
    }

    public function logout(bool $enable = true): LogoutSectionConfigurator
    {
        $logout = new LogoutSectionConfigurator($this->extension, $this, $this->firewalls, $this->name, $enable);
        $logout->firewalls = $this->firewalls;

        return $logout;
    }

    final public function firewall(string $name, string $path = '/'): self
    {
        return $this->extension->firewall($name, $path);
    }
}
