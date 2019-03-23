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

final class FrameworkExtensionConfigurator extends AbstractExtensionConfigurator
{
    public const NAMESPACE = 'framework';

    /**
     * @param string $secret
     *
     * @return $this
     */
    public function secret(string $secret)
    {
        return self::set('secret', $secret);
    }

    public function ide(string $ide)
    {
        return self::set('ide', $ide);
    }

    public function test(bool $test = true)
    {
        return self::set('test', $test);
    }

    public function defaultLocale(string $locale)
    {
        return self::set('default_locale', $locale);
    }

    public function httpMethodOverride(bool $override = true)
    {
        return self::set('http_method_override', $override);
    }

    public function trustedHosts(array $trusted)
    {
        return self::set('trusted_hosts', $trusted);
    }

    public function esi(bool $enabled = true)
    {
        return self::enable('esi', $enabled);
    }

    public function ssi(bool $enabled = true)
    {
        return self::enable('ssi', $enabled);
    }

    public function fragment(bool $enabled = true, string $path = null)
    {
        $fragment['enabled'] = $enabled;
        if ($path) {
            $fragment['path'] = $path;
        }

        return self::set('fragment', $fragment);
    }

    public function csrf(bool $enabled = true)
    {
        return self::enable('csrf', $enabled);
    }

    public function form(bool $enabled = true, bool $csrfProtection = true, string $csrfFieldName = '_token')
    {
        return self::set('form', [
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
        return self::set('request', ['enabled' => $enabled, 'formats' => $formats]);
    }
}
