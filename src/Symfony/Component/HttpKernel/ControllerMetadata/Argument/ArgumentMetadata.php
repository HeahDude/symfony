<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\HttpKernel\ControllerMetadata\Argument;

/**
 * Responsible for storing metadata of an argument.
 *
 * @author Iltar van der Berg <kjarli@gmail.com>
 */
final class ArgumentMetadata implements ArgumentMetadataInterface
{
    /**
     * @var string
     */
    private $argumentName;

    /**
     * @var string
     */
    private $argumentType;

    /**
     * @var bool
     */
    private $isVariadic;

    /**
     * @var bool
     */
    private $hasDefaultValue;

    /**
     * @var mixed
     */
    private $defaultValue;

    /**
     * @param string $argumentName
     * @param string $argumentType
     * @param bool   $isVariadic
     * @param bool   $hasDefaultValue
     * @param mixed  $defaultValue
     */
    public function __construct($argumentName, $argumentType, $isVariadic, $hasDefaultValue, $defaultValue)
    {
        $this->argumentName = $argumentName;
        $this->argumentType = $argumentType;
        $this->isVariadic = $isVariadic;
        $this->hasDefaultValue = $hasDefaultValue;
        $this->defaultValue = $defaultValue;
    }

    /**
     * {@inheritdoc}
     */
    public function getArgumentName()
    {
        return $this->argumentName;
    }

    /**
     * {@inheritdoc}
     */
    public function getArgumentType()
    {
        return $this->argumentType;
    }

    /**
     * {@inheritdoc}
     */
    public function isVariadic()
    {
        return $this->isVariadic;
    }

    /**
     * {@inheritdoc}
     */
    public function hasDefaultValue()
    {
        return $this->hasDefaultValue;
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultValue()
    {
        return $this->defaultValue;
    }
}
