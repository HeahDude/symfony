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
use Symfony\Component\DependencyInjection\Loader\Configurator\AbstractExtensionSectionConfigurator;

/**
 * @internal
 */
class AssetsSectionConfigurator extends AbstractExtensionSectionConfigurator
{
    public const NAMESPACE = 'framework';
    public const SECTION = 'assets';

    protected $package;
    protected $packages = [];

    final public function __construct(AbstractExtensionConfigurator $extension, string $packageName = null)
    {
        $this->package = $packageName;

        parent::__construct($extension);
    }

    final public function versionStrategy(string $strategy)
    {
        return $this->set('version_strategy', $strategy);
    }

    final public function version(string $version)
    {
        return $this->set('version', $version);
    }

    final public function versionFormat(string $format)
    {
        return $this->set('version_format', $format);
    }

    final public function jsonManifestPath(string $path)
    {
        return $this->set('json_manifest_path', $path);
    }

    final public function basePath(string $path)
    {
        return $this->set('base_path', $path);
    }

    /**
     * @param string|array $urls One or more urls
     *
     * @return $this
     */
    final public function baseUrls($urls)
    {
        return $this->set('base_urls', $urls);
    }

    final public function package(string $name)
    {
        if ($this->package) {
            // allow chaining
            return $this->extension->package($name);
        }

        return new class($this, $name) extends AssetsSectionConfigurator {
            public function __destruct()
            {
                $this->extension->packages[$this->package] = $this->config;
            }
        };
    }
}
