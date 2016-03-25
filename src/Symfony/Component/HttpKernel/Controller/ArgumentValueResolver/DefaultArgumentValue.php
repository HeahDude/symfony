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
use Symfony\Component\HttpKernel\ControllerMetadata\Argument\ArgumentMetadataInterface;

/**
 * Returns the default value defined in the action signature if present and no value has been given.
 *
 * @author Iltar van der Berg <kjarli@gmail.com>
 */
final class DefaultArgumentValue implements ArgumentValueResolverInterface
{
    /**
     * {@inheritdoc}
     */
    public function supports(Request $request, ArgumentMetadataInterface $argument)
    {
        return $argument->hasDefaultValue() && !$request->attributes->has($argument->getArgumentName());
    }

    /**
     * {@inheritdoc}
     */
    public function getValue(Request $request, ArgumentMetadataInterface $argument)
    {
        return $argument->getDefaultValue();
    }
}
