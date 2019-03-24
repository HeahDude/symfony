<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Bundle\DebugBundle\DependencyInjection\Loader\Configurator;

use Symfony\Component\DependencyInjection\Loader\Configurator\AbstractExtensionConfigurator;

final class DebugExtensionConfigurator extends AbstractExtensionConfigurator
{
    public const NAMESPACE = 'debug';

    public function maItems(int $max)
    {
        return $this->set('max_items', $max);
    }

    public function minDepth(int $min)
    {
        return $this->set('min_depth', $min);
    }

    public function maxStringLength(int $max)
    {
        return $this->set('max_string_length', $max);
    }

    public function dumpDestination(string $streamUrl)
    {
        return $this->set('dump_destination', $streamUrl);
    }

    /**
     * @param string $appearance "light" or "dark"
     *
     * @return $this
     */
    public function theme(string $appearance)
    {
        return $this->set('theme', $appearance);
    }
}
