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

final class ValidationSectionConfigurator extends AbstractExtensionSectionConfigurator
{
    public const NAMESPACE = 'framework';
    public const SECTION = 'validation';

    public function cache(string $adapter)
    {
        return $this->set('cache', $adapter);
    }

    public function enableAnnotations(bool $enable = true)
    {
        return $this->set('enable_annotations', $enable);
    }

    /**
     * @param string|array $methods One or many static methods
     *
     * @return $this
     */
    public function staticMethods($methods)
    {
        return $this->set('static_method', $methods);
    }

    public function translationDomain(string $domain)
    {
        return $this->set('translation_domain', $domain);
    }

    public function strictEmail(bool $strict = true)
    {
        return $this->set('strict_email', $strict);
    }

    /**
     * @param string $mode "html5", "loose" or "strict"
     *
     * @return $this
     */
    public function emailValidationMode(string $mode)
    {
        return $this->set('email_validation_mode', $mode);
    }

    public function mappingPaths(array $paths)
    {
        return $this->set('mapping', ['paths' => $paths]);
    }
}
