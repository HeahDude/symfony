<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Bundle\FrameworkBundle\EventListener;

use Symfony\Bundle\FrameworkBundle\Console\DebugApplication;
use Symfony\Component\Console\Command\TraceableCommand;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\Console\Event\ConsoleErrorEvent;
use Symfony\Component\Console\Event\ConsoleEvent;
use Symfony\Component\Console\Event\ConsoleTerminateEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Debug\DebugRequestStack;
use Symfony\Component\HttpKernel\Profiler\Profiler;
use Symfony\Component\Stopwatch\Stopwatch;

class ConsoleProfilerListener implements EventSubscriberInterface
{
    private ?\Throwable $error = null;

    public function __construct(
        private Profiler $profiler,
        private DebugRequestStack $requestStack,
        private Stopwatch $stopwatch,
        private bool $onlyExceptions,
        private ?string $commandPatern,
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            ConsoleEvents::COMMAND => ['initialize', 2048],
            ConsoleEvents::ERROR => 'catch',
            ConsoleEvents::TERMINATE => ['profile', -2048],
        ];
    }

    public function initialize(ConsoleEvent $event): void
    {
        if ($this->commandPatern && !preg_match($this->commandPatern, $event->getCommand()->getName())) {
            $this->profiler->disable();
        }
    }

    public function catch(ConsoleErrorEvent $event): void
    {
        $this->error = $event->getError();
    }

    public function profile(ConsoleTerminateEvent $event): void
    {
        $request = $this->requestStack->getCurrentRequest(includeVirtual: true);
        $command = $event->getCommand();

        try {
            $this->stopwatch->stop($command->getName());
        } catch (\LogicException) {
            // noop
        }
        $this->stopwatch->stopSection('_cli');

        if ($this->onlyExceptions && null === $this->error || !$this->profiler->isEnabled()) {
            return;
        }

        if ($command instanceof TraceableCommand) {
            $request->command = $command;
        } else {
            throw new \LogicException(sprintf('The profiler is enabled but the "%s" does not seem to be used.', DebugApplication::class));
        }

        $profile = $this->profiler->collect(
            $request,
            $request->getResponse($event->getExitCode()),
            $this->error
        );
        $this->profiler->saveProfile($profile);

        $output = $event->getOutput();
        if ($output->isVerbose()) {
            // todo make link if possible
            $output->writeln(sprintf('See the profile: %s.', $profile->getToken()));
        }
    }
}
