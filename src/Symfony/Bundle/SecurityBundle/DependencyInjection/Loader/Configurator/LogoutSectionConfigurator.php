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

final class LogoutSectionConfigurator extends FirewallSectionConfigurator
{
    use Traits\FirewallSubSectionTrait;

    private $type = 'logout';

    public function logoutCsrfParameter(string $fieldName)
    {
        $this->configureFirewall('csrf_parameter', $fieldName);

        return $this;
    }

    public function logoutCsrfTokenGenerator(string $serviceId)
    {
        $this->configureFirewall('csrf_token_generator', $serviceId);

        return $this;
    }

    public function logoutCsrfTokenId(string $intention)
    {
        $this->configureFirewall('csrf_token_id', $intention);

        return $this;
    }

    /**
     * @param string $path A relative path or a route name
     *
     * @return $this
     */
    public function logoutPath(string $path)
    {
        $this->configureFirewall('path', $path);

        return $this;
    }

    /**
     * @param string $path A relative path or a route name
     *
     * @return $this
     */
    public function logoutTarget(string $path)
    {
        $this->configureFirewall('target', $path);

        return $this;
    }

    public function logoutSuccessHandler(string $serviceId)
    {
        $this->configureFirewall('success_handler', $serviceId);

        return $this;
    }

    public function logoutInvalidateSession(bool $invalidate = true)
    {
        $this->configureFirewall('invalidate_session', $invalidate);

        return $this;
    }

    public function logoutDeleteCookie(string $name, string $path, string $domain)
    {
        $this->firewalls[$this->name][$this->type]['delete_cookies'][$name] = [
            'path' => $path,
            'domain' => $domain,
        ];

        return $this;
    }

    public function logoutHandlers(string ...$handlersId)
    {
        $this->configureFirewall('handlers', $handlersId);

        return $this;
    }
}
