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

final class PhpErrorsSectionConfigurator extends AbstractExtensionSectionConfigurator
{
    public const NAMESPACE = 'framework';
    public const SECTION = 'php_errors';

    /**
     * @param int|bool $level A to enable/disable or an int for an E_* constant
     *
     * @return $this
     */
    public function log($level = true)
    {
        return $this->set('log', $level);
    }

    public function throw(bool $throw = true)
    {
        return $this->set('throw', $throw);
    }
}
