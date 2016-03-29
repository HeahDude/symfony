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
class ArgumentMetadata
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
     * Returns the name as given in PHP, $foo would yield "foo".
     *
     * @return string
     */
    public function getArgumentName()
    {
        return $this->argumentName;
    }

    /**
     * The PHP class in 5.5+ and additionally the basic type in PHP 7.0+.
     *
     * @return string
     */
    public function getArgumentType()
    {
        return $this->argumentType;
    }

    /**
     * If the argument is defined as "...$variadic".
     *
     * @return bool
     */
    public function isVariadic()
    {
        return $this->isVariadic;
    }

    /**
     * If the argument has a default value.
     *
     * Implies an optional argument when true.
     *
     * @return bool
     */
    public function hasDefaultValue()
    {
        return $this->hasDefaultValue;
    }

    /**
     * The default value of the argument.
     *
     * Make sure to call {@see self::hasDefaultValue()} first to see if a default value is possible.
     *
     * @return mixed
     */
    public function getDefaultValue()
    {
        return $this->defaultValue;
    }
}
