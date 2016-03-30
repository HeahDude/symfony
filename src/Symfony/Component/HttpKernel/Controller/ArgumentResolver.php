<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\HttpKernel\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\ControllerMetadata\Argument\ArgumentMetadataFactoryInterface;

/**
 * Responsible for the resolving of arguments passed to an action.
 *
 * @author Iltar van der Berg <kjarli@gmail.com>
 */
final class ArgumentResolver implements ArgumentResolverInterface
{
    private $argumentMetadataFactory;

    /**
     * @var ArgumentValueResolverInterface[]
     */
    private $argumentValueResolvers;

    public function __construct(ArgumentMetadataFactoryInterface $argumentMetadataFactory = null, array $argumentValueResolvers = array())
    {
        $this->argumentMetadataFactory = $argumentMetadataFactory;
        $this->argumentValueResolvers = $argumentValueResolvers;
    }

    public function getArguments(Request $request, $controller)
    {
        $arguments = array();

        foreach ($this->argumentMetadataFactory->createArgumentMetadata($controller) as $metadata) {
            foreach ($this->argumentValueResolvers as $resolver) {
                if (!$resolver->supports($request, $metadata)) {
                    continue;
                }

                $resolved = $resolver->resolve($request, $metadata);

                if (!$resolved instanceof \Generator) {
                    throw new \InvalidArgumentException(sprintf('%s::resolve() must return a \Generator, %s given.', get_class($resolver), gettype($resolved)));
                }

                foreach ($resolved as $append) {
                    $arguments[] = $append;
                }

                // continue to the next controller argument
                continue 2;
            }

            $representative = $controller;

            if (is_array($representative)) {
                $representative = sprintf('%s::%s()', get_class($representative[0]), $representative[1]);
            } elseif (is_object($representative)) {
                $representative = get_class($representative);
            }

            throw new \RuntimeException(sprintf('Controller "%s" requires that you provide a value for the "$%s" argument (because there is no default value or because there is a non optional argument after this one).', $representative, $metadata->getArgumentName()));
        }

        return $arguments;
    }
}
