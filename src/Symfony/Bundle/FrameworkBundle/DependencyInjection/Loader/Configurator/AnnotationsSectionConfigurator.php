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

final class AnnotationsSectionConfigurator extends AbstractExtensionSectionConfigurator
{
    public const NAMESPACE = 'framework';
    public const SECTION = 'annotations';

    public function cache(string $adapter)
    {
        return $this->set('cache', $adapter);
    }

    public function fileCacheDir(string $dir)
    {
        return $this->set('file_cache_dir', $dir);
    }

    public function debug(bool $enable = true)
    {
        return $this->set('debug', $enable);
    }
}
