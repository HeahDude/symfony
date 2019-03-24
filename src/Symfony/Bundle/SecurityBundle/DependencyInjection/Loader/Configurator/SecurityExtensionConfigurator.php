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

use Symfony\Bridge\Doctrine\DependencyInjection\Security\UserProvider\EntityFactory;
use Symfony\Component\DependencyInjection\Loader\Configurator\AbstractExtensionConfigurator;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

final class SecurityExtensionConfigurator extends AbstractExtensionConfigurator
{
    public const NAMESPACE = 'security';

    private $accessDecisionManager = [];
    private $roleHierarchy = [];
    private $encoders;
    private $providers;
    private $firewalls;
    private $accessControl;

    public function __construct(ContainerConfigurator $configurator)
    {
        parent::__construct($configurator);

        $this->encoders = new \ArrayObject();
        $this->providers = new \ArrayObject();
        $this->firewalls = new \ArrayObject();
        $this->accessControl = new \ArrayObject();
    }

    public function __destruct()
    {
        $this
            ->set('access_decision_manager', $this->accessDecisionManager)
            ->set('role_hierarchy', $this->roleHierarchy)
            ->set('encoders', $this->encoders->getArrayCopy())
            ->set('providers', $this->providers->getArrayCopy())
            ->set('firewalls', $this->firewalls->getArrayCopy())
            ->set('access_control', $this->accessControl->getArrayCopy())
        ;

        parent::__destruct();
    }

    public function accessDeniedUrl(string $url)
    {
        return $this->set('access_denied_url', $url);
    }

    /**
     * @param string $strategy One of SessionAuthenticationStrategy constant.
     *
     * @return $this
     */
    public function sessionFixationStrategy(string $strategy)
    {
        return $this->set('session_fixation_strategy', $strategy);
    }

    public function hideUserNotFound(bool $hide = true)
    {
        return $this->set('hide_user_not_found', $hide);
    }

    public function alwaysAuthenticateBeforeGranting(bool $authenticate = true)
    {
        return $this->set('always_authenticate_before_granting', $authenticate);
    }

    public function eraseCredentials(bool $erase = true)
    {
        return $this->set('erase_credentials', $erase);
    }

    /**
     * @param string $strategy One of AccessDecisionManager constants.
     *
     * @return $this
     */
    public function accessDecisionManagerStrategy(string $strategy)
    {
        $this->accessDecisionManager['strategy'] = $strategy;
        unset($this->accessDecisionManager['service']);

        return $this;
    }

    public function accessDecisionManagerService(string $id)
    {
        $this->accessDecisionManager['service'] = $id;
        unset($this->accessDecisionManager['strategy']);

        return $this;
    }

    public function allowIfAllAbstain(bool $allow = true)
    {
        $this->accessDecisionManager['allow_if_all_abstain'] = $allow;

        return $this;
    }

    public function allowIfEqualGrantedDenied(bool $allow = true)
    {
        $this->accessDecisionManager['allow_if_equal_granted_denied'] = $allow;

        return $this;
    }

    public function roleHierarchy(string $role, string ...$inheritedRoles)
    {
        $this->roleHierarchy[$role] = $inheritedRoles;

        return $this;
    }

    public function encoder(string $class, string $algorithm = 'bcrypt'): EncoderSectionConfigurator
    {
        return new EncoderSectionConfigurator($this, $this->encoders, $class, $algorithm);
    }

    public function inMemoryProvider(string $name): InMemoryProviderSectionConfigurator
    {
        return new InMemoryProviderSectionConfigurator($this, $this->providers, $name);
    }

    public function ldapProvider(string $name): LdapProviderSectionConfigurator
    {
        return new LdapProviderSectionConfigurator($this, $this->providers, $name);
    }

    public function entityProvider(string $name, string $class, string $property = null, string $managerName = null)
    {
        if (!class_exists(EntityFactory::class)) {
            throw new \LogicException(sprintf('You cannot use "%s" when the Doctrine Bundle is not installed. Try run "composer require doctrine/doctrine-bundle".', __METHOD__));
        }

        $this->providers[$name] = ['entity' => [
            'class' => $class,
            'property' => $property,
            'manager_name' => $managerName,
        ]];

        return $this;
    }

    public function chainProvider(string $name, string ...$providers)
    {
        $this->providers[$name] = ['chain' => ['providers' => $providers]];

        return $this;
    }

    public function serviceProvider(string $name, string $id)
    {
        $this->providers[$name] = ['id' => $id];

        return $this;
    }

    public function firewall(string $name, string $path = '/'): FirewallSectionConfigurator
    {
        return new FirewallSectionConfigurator($this, $this->firewalls, $name, $path);
    }

    public function accessControl(): AccessControlSectionConfigurator
    {
        return new AccessControlSectionConfigurator($this, $this->accessControl);
    }
}
