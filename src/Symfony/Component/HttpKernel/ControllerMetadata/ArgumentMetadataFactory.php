<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\HttpKernel\ControllerMetadata;

/**
 * Builds method argument data.
 *
 * @author Iltar van der Berg <kjarli@gmail.com>
 */
final class ArgumentMetadataFactory implements ArgumentMetadataFactoryInterface
{
    public function createArgumentMetadata($controller)
    {
        $arguments = array();

        if (is_array($controller)) {
            $reflection = new \ReflectionMethod($controller[0], $controller[1]);
        } elseif (is_object($controller) && !$controller instanceof \Closure) {
            $reflection = (new \ReflectionObject($controller))->getMethod('__invoke');
        } else {
            $reflection = new \ReflectionFunction($controller);
        }

        foreach ($reflection->getParameters() as $param) {
            $arguments[] = new ArgumentMetadata($param->getName(), $this->getType($param), $this->isVariadic($param), $this->hasDefaulValue($param), $this->getDefaulValue($param));
        }

        return $arguments;
    }

    /**
     * Tries to detect if the argument is variadic.
     *
     * @param \ReflectionParameter $parameter
     *
     * @return bool
     */
    private function isVariadic(\ReflectionParameter $parameter)
    {
        return PHP_VERSION_ID >= 50600 && $parameter->isVariadic();
    }

    /**
     * Determine if there's a default value.
     *
     * @param \ReflectionParameter $parameter
     *
     * @return bool
     */
    private function hasDefaulValue(\ReflectionParameter $parameter)
    {
        return $parameter->isDefaultValueAvailable();
    }

    /**
     * Tries to find the default value.
     *
     * @param \ReflectionParameter $parameter
     *
     * @return mixed|null
     */
    private function getDefaulValue(\ReflectionParameter $parameter)
    {
        return $this->hasDefaulValue($parameter) ? $parameter->getDefaultValue() : null;
    }

    /**
     * Tries to find the type associated with it.
     *
     * @param \ReflectionParameter $parameter
     *
     * @return null|string
     */
    private function getType(\ReflectionParameter $parameter)
    {
        if (PHP_VERSION_ID >= 70000) {
            return $parameter->hasType() ? (string) $parameter->getType() : null;
        }

        $refClass = $parameter->getClass();

        return $refClass ? $refClass->getName() : null;
    }
}
