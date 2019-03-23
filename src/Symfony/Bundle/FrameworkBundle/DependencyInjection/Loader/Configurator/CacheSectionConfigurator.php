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

final class CacheSectionConfigurator extends AbstractExtensionSectionConfigurator
{
    public const NAMESPACE = 'framework';
    public const SECTION = 'cache';

    private $pools;

    public function __construct(AbstractExtensionConfigurator $extension)
    {
        parent::__construct($extension);

        $this->pools = new \ArrayObject();
    }

    public function __destruct()
    {
        $this->set('pools', $this->pools->getArrayCopy());

        parent::__destruct();
    }

    public function prefixSeed(string $prefix)
    {
        return $this->set('prefix_seed', $prefix);
    }

    public function app(string $adapter)
    {
        return $this->set('app', $adapter);
    }

    public function system(string $adapter)
    {
        return $this->set('system', $adapter);
    }

    public function directory(string $path)
    {
        return $this->set('directory', $path);
    }

    public function defaultDoctrineProvider(string $url)
    {
        return $this->set('default_doctrine_provider', $url);
    }

    public function defaultPsr6Provider(string $id)
    {
        return $this->set('default_psr6_provider', $id);
    }

    public function defaultRedisProvider(string $url)
    {
        return $this->set('default_redis_provider', $url);
    }

    public function defaultMemcachedProvider(string $url)
    {
        return $this->set('default_memcached_provider', $url);
    }

    public function defaultPdoProvider(string $name)
    {
        return $this->set('default_pdo_provider', $name);
    }

    public function pool(string $name)
    {
        return new PoolSectionConfigurator($this, $this->pools, $name);
    }
}

final class PoolSectionConfigurator extends AbstractExtensionSectionConfigurator
{
    private $name;
    private $pools;

    public function __construct(CacheSectionConfigurator $extension, \ArrayObject $pools, string $name)
    {
        parent::__construct($extension);

        $this->name = $name;
        $this->pools = $pools;
    }

    public function __destruct()
    {
        $this->pools[$this->name] = $this->config;
    }

    public function adapter(string $name)
    {
        $this->set('adapter', $name);
    }

    public function tags(string $tags)
    {
        $this->set('tags', $tags);
    }

    public function public(bool $public = true)
    {
        $this->set('public', $public);
    }

    public function defaultLifetime(int $time)
    {
        $this->set('default_lifetime', $time);
    }

    public function provider(string $name)
    {
        $this->set('provider', $name);
    }

    public function clearer(string $name)
    {
        $this->set('clearer', $name);
    }

    /**
     * Allow chaining.
     */
    public function pool(string $name): self
    {
        return $this->extension->pool($name);
    }
}
