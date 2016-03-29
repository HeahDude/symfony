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
use Symfony\Component\HttpKernel\ControllerMetadata\Argument\ArgumentMetadata;

/**
 * Responsible for the value of an argument based on its metadata.
 *
 * @author Iltar van der Berg <kjarli@gmail.com>
 */
interface ArgumentValueResolverInterface
{
    /**
     * Should return true if this resolver can resolve the value for the given ArgumentMetadata.
     *
     * @param Request          $request
     * @param ArgumentMetadata $argument
     *
     * @return bool
     */
    public function supports(Request $request, ArgumentMetadata $argument);

    /**
     * Get the actual value for the argument.
     *
     * The return must always be an array:
     *  - return array($myArgumentValue);
     *
     * @param Request          $request
     * @param ArgumentMetadata $argument
     *
     * @return mixed[]
     */
    public function getValue(Request $request, ArgumentMetadata $argument);
}
