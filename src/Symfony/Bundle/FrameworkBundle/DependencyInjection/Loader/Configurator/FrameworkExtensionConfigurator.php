<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Bundle\FrameworkBundle\DependencyInjection\Loader\Configurator;

use Symfony\Component\DependencyInjection\Loader\Configurator\AbstractExtensionConfigurator;
use Symfony\Component\DependencyInjection\Loader\Configurator\Traits\CanBeEnabledTrait;

final class FrameworkExtensionConfigurator extends AbstractExtensionConfigurator
{
    use CanBeEnabledTrait;

    public const NAMESPACE = 'framework';

    /**
     * @param string $secret
     *
     * @return $this
     */
    public function secret(string $secret)
    {
        return $this->set('secret', $secret);
    }

    public function ide(string $ide)
    {
        return $this->set('ide', $ide);
    }

    public function test(bool $test = true)
    {
        return $this->set('test', $test);
    }

    public function defaultLocale(string $locale)
    {
        return $this->set('default_locale', $locale);
    }

    public function httpMethodOverride(bool $override = true)
    {
        return $this->set('http_method_override', $override);
    }

    public function trustedHosts(array $trusted)
    {
        return $this->set('trusted_hosts', $trusted);
    }

    public function esi(bool $enabled = true)
    {
        return $this->enable($enabled, 'esi');
    }

    public function ssi(bool $enabled = true)
    {
        return $this->enable($enabled, 'ssi');
    }

    public function fragment(bool $enabled = true, string $path = null)
    {
        $fragment['enabled'] = $enabled;
        if ($path) {
            $fragment['path'] = $path;
        }

        return $this->set('fragment', $fragment);
    }

    public function csrf(bool $enabled = true)
    {
        return $this->enable($enabled, 'csrf');
    }

    public function form(bool $enabled = true, bool $csrfProtection = true, string $csrfFieldName = '_token')
    {
        return $this->set('form', [
            'enabled' => $enabled,
            'csrf_protection' => [
                'enabled' => $csrfProtection,
                'field_name' => $csrfFieldName,
            ],
        ]);
    }

    /**
     * @param bool  $enabled
     * @param array $formats An array of
     *
     * @return $this
     */
    public function request(bool $enabled = true, array $formats = [])
    {
        return $this->set('request', ['enabled' => $enabled, 'formats' => $formats]);
    }
}
