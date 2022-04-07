<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\HttpKernel\Debug;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Obfuscates {@see CliRequest} from services depending on the stack state
 * (e.g. some ORM listener depending on the current request being set may lead
 * to unwanted side effects).
 */
final class DebugRequestStack extends RequestStack
{
    /**
     * {@inheritdoc}
     *
     * @param bool $includeVirtual Allows faking request (e.g. in CLI context)
     */
    public function getCurrentRequest(bool $includeVirtual = false): Request|CliRequest|null
    {
        if ($includeVirtual) {
            return parent::getCurrentRequest();
        }

        return ($request = parent::getCurrentRequest()) instanceof CliRequest ? null : $request;
    }

    /**
     * {@inheritdoc}
     *
     * @param bool $includeVirtual Allows faking request (e.g. in CLI context)
     */
    public function getMainRequest(bool $includeVirtual = false): Request|CliRequest|null
    {
        if ($includeVirtual) {
            return parent::getMainRequest();
        }

        return ($request = parent::getMainRequest()) instanceof CliRequest ? null : $request;
    }

    /**
     * @internal
     */
    public function pushCommand(bool $hasRootTrace): void
    {
        $this->push(new CliRequest($hasRootTrace));
    }
}
