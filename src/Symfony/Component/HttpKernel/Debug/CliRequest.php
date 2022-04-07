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

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Command\TraceableCommand;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Enables profiling commands with a virtual request.
 *
 * @internal
 */
class CliRequest extends Request
{
    public TraceableCommand $command;

    public function __construct(bool $hasRootTrace)
    {
        $attributes = ['_stopwatch_token' => '_cli'];

        if ($hasRootTrace) {
            $attributes['_stopwatch_root_trace'] = true;
        }

        parent::__construct(attributes: $attributes, server: $_SERVER);
    }

    public function getResponse(int $exitCode): Response
    {
        return new Response(content: null, status: Command::SUCCESS === $exitCode ? 200 : 500);
    }

    public function getMethod(): string
    {
        // fake method to allow filtering profiles
        return 'CLI';
    }

    public function getUri(): string
    {
        return trim(sprintf('%s %s', $this->server->get('SCRIPT_NAME'), $this->command?->getFullRepresentation()));
    }

    public function hasRootTrace(): ?bool
    {
        return $this->attributes->get('_stopwatch_root_trace');
    }
}
