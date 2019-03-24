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

final class EncoderSectionConfigurator extends AbstractExtensionSectionConfigurator
{
    public const NAMESPACE = 'security';
    public const SECTION = 'encoders';

    private $encoders;

    public function __construct(SecurityExtensionConfigurator $extension, \ArrayObject $encoders, string $class, string $algorithm)
    {
        parent::__construct($extension);

        $this->encoders = $encoders;
        $this->set('class', $class);
        $this->set('algorithm', $algorithm);
    }

    public function __destruct()
    {
        $this->encoders[] = $this->config;
    }

    public function hashAlgorithm(string $name)
    {
        return $this->set('hash_algorithm', $name);
    }

    public function keyLength(int $length)
    {
        return $this->set('key_length', $length);
    }

    public function ignoreCase(bool $ignore = true)
    {
        return $this->set('ignore_case', $ignore);
    }

    public function encodeAsBase64(bool $encode = true)
    {
        return $this->set('encode_as_base64', $encode);
    }

    public function iterations(int $iterations)
    {
        return $this->set('iterations', $iterations);
    }

    public function cost(int $cost)
    {
        return $this->set('cost', $cost);
    }

    public function memoryCost(int $cost)
    {
        return $this->set('memory_cost', $cost);
    }

    public function timeCost(int $cost)
    {
        return $this->set('time_cost', $cost);
    }

    public function threads(string $threads)
    {
        return $this->set('threads', $threads);
    }

    public function service(string $id)
    {
        $this->config = [];

        return $this->set('id', $id);
    }
}
