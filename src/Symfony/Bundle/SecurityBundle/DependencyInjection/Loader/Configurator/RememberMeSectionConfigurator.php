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

use Symfony\Component\HttpFoundation\Cookie;

final class RememberMeSectionConfigurator extends FirewallSectionConfigurator
{
    use Traits\FirewallSubSectionTrait;

    private $type = 'remember_me';

    public function __construct(SecurityExtensionConfigurator $extension, FirewallSectionConfigurator $firewall, \ArrayObject $firewalls, string $name, bool $enable, string $secret)
    {
        parent::__construct($extension, $firewalls, $name);

        $this->firewall = $firewall;

        if ($enable) {
            $this->firewalls[$name][$this->type] = ['secret' => $secret];
        }
    }

    public function rememberMeTokenProvider(string $name)
    {
        $this->configureFirewall('token_provider', $name);

        return $this;
    }

    public function rememberMeUserProviders(string ...$names)
    {
        $this->configureFirewall('user_providers', $names);

        return $this;
    }

    public function rememberMeCatchExceptions(bool $catch = true)
    {
        $this->configureFirewall('catch_exceptions', $catch);

        return $this;
    }

    /**
     * @param string|bool $secure "auto" or a boolean
     *
     * @return $this
     */
    public function rememberMeSecure($secure = true)
    {
        $this->configureFirewall('secure', $secure);

        return $this;
    }

    /**
     * @param string $samesite One of the {@see Cookie} constants.
     *
     * @return $this
     */
    public function rememberMeSamesite(string $samesite)
    {
        $this->configureFirewall('samesite', $samesite);

        return $this;
    }

    /**
     * @param string $samesite One of the {@see Cookie} constants.
     *
     * @return $this
     */
    public function rememberMeCookie(string $name, int $lifetime = 31536000, string $path = '', string $domain = null)
    {
        $this->configureFirewall('name', $name);
        $this->configureFirewall('lifetime', $lifetime);
        $this->configureFirewall('path', $path);
        $this->configureFirewall('domain', $domain);

        return $this;
    }

    public function rememberMeAlways(bool $always = true)
    {
        $this->configureFirewall('always_remember_me', $always);

        return $this;
    }

    public function rememberMeParameter(string $fieldName)
    {
        $this->configureFirewall('remember_me_parameter', $fieldName);

        return $this;
    }
}
