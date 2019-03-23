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
use Symfony\Component\DependencyInjection\Loader\Configurator\Traits\CanBeEnabledTrait;

final class SerializerSectionConfigurator extends AbstractExtensionSectionConfigurator
{
    use CanBeEnabledTrait;
    use Traits\AnnotationTrait;

    public const NAMESPACE = 'framework';
    public const SECTION = 'serializer';

    public function cache(string $adapter)
    {
        return $this->set('cache', $adapter);
    }

    public function nameConverter(string $converterId)
    {
        return $this->set('name_converter', $converterId);
    }

    public function circularReferenceHandler(string $handlerId)
    {
        return $this->set('circular_reference_handler', $handlerId);
    }

    public function maxDepthHandler(string $handlerId)
    {
        return $this->set('max_depth_handler', $handlerId);
    }
}
