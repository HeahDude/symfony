<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Bundle\FrameworkBundle\DependencyInjection\Loader\Configurator\Traits;

trait AnnotationTrait
{
    public function enableAnnotations(bool $enable = true)
    {
        return $this->set('enable_annotations', $enable);
    }

    public function mappingPaths(array $paths)
    {
        return $this->set('mapping', ['paths' => $paths]);
    }
}
