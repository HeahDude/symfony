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

use Symfony\Component\DependencyInjection\Loader\Configurator\AbstractExtensionSectionConfigurator;

final class InMemoryProviderSectionConfigurator extends AbstractExtensionSectionConfigurator
{
    public const NAMESPACE = 'security';
    public const SECTION = 'providers';

    private $users = [];
    private $providers;
    private $name;

    public function __construct(SecurityExtensionConfigurator $extension, \ArrayObject $providers, string $name)
    {
        parent::__construct($extension);

        $this->providers = $providers;
        $this->name = $name;
    }

    public function __destruct()
    {
        $this->providers[$this->name] = ['memory' => ['users' => $this->users]];
    }

    public function user(string $name, string $password, string ...$roles)
    {
        $this->users[$name] = ['password' => $password, 'roles' => $roles];

        return $this;
    }
}
