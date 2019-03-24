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
class FormLoginSectionConfigurator extends FirewallSectionConfigurator
{
    use Traits\LoginHandlersSectionTrait;

    private $type = 'form_login';

    public function formLoginUsernameParameter(string $fieldName)
    {
        $this->configureFirewall('username_parameter', $fieldName);

        return $this;
    }

    public function formLoginPasswordParameter(string $fieldName)
    {
        $this->configureFirewall('password_parameter', $fieldName);

        return $this;
    }

    public function formLoginCsrfParameter(string $fieldName)
    {
        $this->configureFirewall('csrf_parameter', $fieldName);

        return $this;
    }

    public function formLoginCsrfTokenId(string $intention)
    {
        $this->configureFirewall('csrf_token_id', $intention);

        return $this;
    }

    public function formLoginPostOnly(bool $postOnly = true)
    {
        $this->configureFirewall('post_only', $postOnly);

        return $this;
    }
}
