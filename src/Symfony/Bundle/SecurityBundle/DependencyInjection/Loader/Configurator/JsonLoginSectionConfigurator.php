<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Bundle\SecurityBundle\DependencyInjection\Loader\Configurator;

/**
 * @internal
 */
class JsonLoginSectionConfigurator extends FirewallSectionConfigurator
{
    use Traits\LoginHandlersSectionTrait;

    private $type = 'json_login';

    public function jsonLoginUsernamePath(string $path)
    {
        $this->configureFirewall('username_path', $path);

        return $this;
    }

    public function jsonLoginPasswordPath(string $path)
    {
        $this->configureFirewall('password_path', $path);

        return $this;
    }
}
