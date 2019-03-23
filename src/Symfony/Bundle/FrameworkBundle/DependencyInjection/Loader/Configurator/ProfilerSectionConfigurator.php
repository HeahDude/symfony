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

final class ProfilerSectionConfigurator extends AbstractExtensionSectionConfigurator
{
    public const NAMESPACE = 'framework';
    public const SECTION = 'profiler';

    public function collect(bool $collect = true)
    {
        return $this->set('collect', $collect);
    }

    public function onlyExceptions(bool $onlyExceptions = true)
    {
        return $this->set('only_exceptions', $onlyExceptions);
    }

    public function onlyMasterRequests(bool $onlyMasterRequests = true)
    {
        return $this->set('only_master_requests', $onlyMasterRequests);
    }

    public function dsn(string $dsn)
    {
        return $this->set('dsn', $dsn);
    }
}
