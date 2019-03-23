<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\DependencyInjection\Loader\Configurator\Traits;

trait CanBeEnabledTrait
{
    /**
     * Enables/disables an extension or one of its section.
     *
     * @return $this
     */
    final public function enable(bool $enable, string $section = null)
    {
        if ($section) {
            $this->config[$section]['enabled'] = $enable;
        } else {
            $this->config['enabled'] = $enable;
        }

        return $this;
    }
}
