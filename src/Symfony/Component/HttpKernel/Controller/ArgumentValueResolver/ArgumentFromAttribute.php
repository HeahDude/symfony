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
 * Grabs a non-variadic value from the request and returns it.
 *
 * Opposite of {@see VariadicArgumentFromAttribute}.
 *
 * @author Iltar van der Berg <kjarli@gmail.com>
 */
final class ArgumentFromAttribute implements ArgumentValueResolverInterface
{
    /**
     * {@inheritdoc}
     */
    public function supports(Request $request, ArgumentMetadata $argument)
    {
        return !$argument->isVariadic() && $request->attributes->has($argument->getArgumentName());
    }

    /**
     * {@inheritdoc}
     */
    public function getValue(Request $request, ArgumentMetadata $argument)
    {
        return array($request->attributes->get($argument->getArgumentName()));
    }
}
