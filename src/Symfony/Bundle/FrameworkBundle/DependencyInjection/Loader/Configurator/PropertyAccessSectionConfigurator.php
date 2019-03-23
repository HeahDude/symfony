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

final class PropertyAccessSectionConfigurator extends AbstractExtensionSectionConfigurator
{
    public const NAMESPACE = 'framework';
    public const SECTION = 'property_access';

    public function magicCall(bool $enable = true)
    {
        return $this->set('magic_call', $enable);
    }

    public function throwExceptionOnInvalidIndex(bool $throw = true)
    {
        return $this->set('throw_exception_on_invalid_index', $throw);
    }
}
