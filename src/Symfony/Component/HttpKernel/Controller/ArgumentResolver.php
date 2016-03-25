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
    /**
     * @var ArgumentMetadataFactoryInterface
     */
    private $argumentMetadataFactory;

    /**
     * @var ArgumentValueResolverInterface[]
     */
    private $argumentValueResolvers;

    /**
     * @param ArgumentMetadataFactoryInterface $argumentMetadataFactory
     * @param ArgumentValueResolverInterface[] $argumentValueResolvers
     */
    public function __construct(ArgumentMetadataFactoryInterface $argumentMetadataFactory = null, array $argumentValueResolvers = [])
    {
        $this->argumentMetadataFactory = $argumentMetadataFactory;
        $this->argumentValueResolvers = $argumentValueResolvers;
    }

    /**
     * {@inheritdoc}
     */
    public function getArguments(Request $request, $controller)
    {
        $arguments = array();

        foreach ($this->argumentMetadataFactory->createArgumentMetadata($controller) as $metadata) {
            $isResolved = false;
            foreach ($this->argumentValueResolvers as $resolver) {
                if ($resolver->supports($request, $metadata)) {
                    $resolved = $resolver->getValue($request, $metadata);

                    // variadic is a special case, always being the last and being an array
                    if (is_array($resolved) && $metadata->isVariadic()) {
                        return array_merge($arguments, $resolved);
                    }

                    $arguments[] = $resolved;
                    $isResolved = true;
                    break;
                }
            }

            if (!$isResolved) {
                $repr = $controller;

                if (is_array($controller)) {
                    $repr = sprintf('%s::%s()', get_class($controller[0]), $controller[1]);
                } elseif (is_object($controller)) {
                    $repr = get_class($controller);
                }

                throw new \RuntimeException(sprintf('Controller "%s" requires that you provide a value for the "$%s" argument (because there is no default value or because there is a non optional argument after this one).', $repr, $metadata->getArgumentName()));
            }
        }

        return $arguments;
    }
}
