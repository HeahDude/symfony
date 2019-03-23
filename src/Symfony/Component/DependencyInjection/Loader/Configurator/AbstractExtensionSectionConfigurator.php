<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

abstract class AbstractExtensionSectionConfigurator extends AbstractExtensionConfigurator
{
    public const SECTION = 'abstract';

    private $extension;

    public function __construct(AbstractExtensionConfigurator $extension)
    {
        if (static::NAMESPACE !== $extension::NAMESPACE) {
            throw new \InvalidArgumentException(sprintf('The section configurator "%s" is not in the same namespace "%s" than the given extension configurator "%s" declared in "%s".', static::class, static::NAMESPACE, \get_class($extension), $extension::NAMESPACE));
        }

        parent::__construct($extension->configurator);

        $this->extension = $extension;
    }

    public function __destruct()
    {
        $this->extension->set(static::SECTION, $this->config);
    }
}
