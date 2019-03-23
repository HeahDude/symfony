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

use Symfony\Component\DependencyInjection\Loader\Configurator\AbstractExtensionSectionConfigurator;
use Symfony\Component\HttpFoundation\Cookie;

final class SessionSectionConfigurator extends AbstractExtensionSectionConfigurator
{
    public const NAMESPACE = 'framework';
    public const SECTION = 'session';

    public function storageId(string $id)
    {
        return $this->set('storage_id', $id);
    }

    public function handlerId(string $id)
    {
        return $this->set('handler_id', $id);
    }

    public function name(string $name)
    {
        return $this->set('name', $name);
    }

    public function cookieLifetime(string $time)
    {
        return $this->set('cookie_lifetime', $time);
    }

    public function cookiePath(string $path)
    {
        return $this->set('cookie_path', $path);
    }

    public function cookieDomain(string $domain)
    {
        return $this->set('cookie_domain', $domain);
    }

    /**
     * @param bool|string $secure A boolean or "auto"
     *
     * @return $this
     */
    public function cookieSecure($secure = true)
    {
        return $this->set('cookie_secure', $secure);
    }

    /**
     * @param string $secure Default null, defined as a Cookie constant.
     *                       Cookie::SAMESITE_LAX or Cookie::SAMESITE_STRICT
     *
     * @return $this
     */
    public function cookieSameSite(string $secure = Cookie::SAMESITE_STRICT)
    {
        return $this->set('cookie_samesite', $secure);
    }

    public function useCookies(bool $use = true)
    {
        return $this->set('use_cookies', $use);
    }

    public function gcDivisor(string $divisor)
    {
        return $this->set('gc_divisor', $divisor);
    }

    public function gcProbability(string $probability)
    {
        return $this->set('gc_probability', $probability);
    }

    public function gcMaxlifetime(string $time)
    {
        return $this->set('gc_maxlifetime', $time);
    }

    public function savePath(string $path)
    {
        return $this->set('save_path', $path);
    }

    public function metadataUpdateThreshold(int $threshold)
    {
        return $this->set('metadata_update_threshold', $threshold);
    }

    public function sidLength(int $length)
    {
        return $this->set('sid_length', $length);
    }

    public function sidBitsPerCharacter(int $bits)
    {
        return $this->set('sid_bits_per_character', $bits);
    }
}
