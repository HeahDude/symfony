<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\HttpKernel\Controller\ArgumentValueResolver;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\Argument\ArgumentMetadata;

/**
 * Grabs the variadic value from the request and returns it.
 *
 * Opposite of {@see ArgumentFromAttribute}.
 *
 * @author Iltar van der Berg <kjarli@gmail.com>
 */
final class VariadicArgumentFromAttribute implements ArgumentValueResolverInterface
{
    /**
     * {@inheritdoc}
     */
    public function supports(Request $request, ArgumentMetadata $argument)
    {
        return $argument->isVariadic() && $request->attributes->has($argument->getArgumentName());
    }

    /**
     * {@inheritdoc}
     */
    public function getValue(Request $request, ArgumentMetadata $argument)
    {
        $value = $request->attributes->get($argument->getArgumentName());

        if (!is_array($value)) {
            throw new \InvalidArgumentException(sprintf('The action argument "...$%1$s" is required to be an array, the request attribute "%1$s" contains a type of "%2$s" instead.', $argument->getArgumentName(), gettype($value)));
        }

        return $value;
    }
}
