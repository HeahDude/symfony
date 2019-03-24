<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Bundle\SecurityBundle\DependencyInjection\Loader\Configurator\Traits;

trait LoginSectionTrait
{
    use FirewallSubSectionTrait;

    /**
     * @param string $path A relative path or a route name
     *
     * @return $this
     */
    public function loginCheckPath(string $path)
    {
        $this->configureFirewall('check_path', $path);

        return $this;
    }

    public function loginUseForward(bool $useForward = true)
    {
        $this->configureFirewall('use_forward', $useForward);

        return $this;
    }

    public function loginRequirePreviousSession(bool $require = true)
    {
        $this->configureFirewall('require_previous_session', $require);

        return $this;
    }

    public function loginAlwaysUseDefaultTargetPath(bool $always = true)
    {
        $this->configureFirewall('always_use_default_target_path', $always);

        return $this;
    }

    /**
     * @param string $path A relative path or a route name
     *
     * @return $this
     */
    public function loginDefaultTargetPath(string $path)
    {
        $this->configureFirewall('default_target_path', $path);

        return $this;
    }

    /**
     * @param string $path A relative path or a route name
     *
     * @return $this
     */
    public function loginPath(string $path)
    {
        $this->configureFirewall('login_path', $path);

        return $this;
    }

    public function loginTargetPathParameter(string $fieldName)
    {
        $this->configureFirewall('target_path_parameter', $fieldName);

        return $this;
    }

    public function loginUseReferer(bool $useReferer = true)
    {
        $this->configureFirewall('use_referer', $useReferer);

        return $this;
    }

    /**
     * @param string $path A relative path or a route name
     *
     * @return $this
     */
    public function loginFailurePath(string $path)
    {
        $this->configureFirewall('failure_path', $path);

        return $this;
    }

    public function loginFailurePathParameter(string $fieldName)
    {
        $this->configureFirewall('failure_path_parameter', $fieldName);

        return $this;
    }
}
