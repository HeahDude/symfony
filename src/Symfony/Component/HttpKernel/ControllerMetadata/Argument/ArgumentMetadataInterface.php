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
interface ArgumentMetadataInterface
{
    /**
     * Returns the name as given in PHP, $foo would yield "foo".
     *
     * @return string
     */
    public function getArgumentName();

    /**
     * The PHP class in 5.5+ and additionally the basic type in PHP 7.0+.
     *
     * @return string
     */
    public function getArgumentType();

    /**
     * If the argument is defined as "...$variadic".
     *
     * @return bool
     */
    public function isVariadic();

    /**
     * If the argument has a default value.
     *
     * Implies an optional argument when true.
     *
     * @return bool
     */
    public function hasDefaultValue();

    /**
     * The default value of the argument.
     *
     * Make sure to call {@see self::hasDefaultValue()} first to see if a default value is possible.
     *
     * @return mixed
     */
    public function getDefaultValue();
}
