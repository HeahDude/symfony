<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Bundle\FrameworkBundle\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Command\TraceableCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpKernel\Debug\DebugRequestStack;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Stopwatch\Stopwatch;

class DebugApplication extends Application
{
    private ?Stopwatch $stopwatch = null;

    public function __construct(KernelInterface $kernel, private bool $trace = false)
    {
        parent::__construct($kernel);

        if (!\class_exists(Stopwatch::class)) {
            throw new \InvalidArgumentException(sprintf('The "%s" requires the Symfony Stopwatch component. Try running "composer require --dev symfony/stopwatch".', __CLASS__));
        }

        $this->stopwatch = new Stopwatch(morePrecision: true);
        $kernel->setStopwatch($this->stopwatch, $this->trace);
    }

    /**
     * {@inheritdoc}
     */
    public function run(InputInterface $input = null, OutputInterface $output = null): int
    {
        $_ENV['APP_START_TIME'] = microtime(as_float: true);

        $autoExit = $this->isAutoExitEnabled();
        $this->setAutoExit(false);

        if ($this->trace) {
            $this->stopwatch->openSection();
        }

        $exitCode = parent::run($input, $output);

        try {
            $this->stopwatch->stopSection('_cli');

            if ($output->isVerbose()) {
                // profiler is not enabled, resume info
                $output->writeln($this->stopwatch->getSectionEvents('_cli'));
            }
        } catch (\LogicException) {
            // noop
        }

        if ($autoExit) {
            if ($exitCode > 255) {
                $exitCode = 255;
            }

            exit($exitCode);
        }

        return $exitCode;
    }

    /**
     * {@inheritdoc}
     */
    public function get(string $name): Command
    {
        $command = parent::get($name);

        if (!$command instanceof TraceableCommand) {
            $command = new TraceableCommand($command, $this->stopwatch);
        }

        return $command;
    }

    /**
     * {@inheritdoc}
     */
    public function add(Command $command): ?Command
    {
        if (!$command instanceof TraceableCommand) {
            $command = new TraceableCommand($command, $this->stopwatch);
        }

        return parent::add($command);
    }

    /**
     * {@inheritdoc}
     */
    public function find(string $name): Command
    {
        if ($this->trace) {
            $e = $this->stopwatch->start('find_command.'.$name, 'console');
        }

        $command = parent::find($name);

        if ($this->trace) {
            $e->stop();
        }

        return $command;
    }

    /**
     * {@inheritdoc}
     */
    protected function registerCommands(): void
    {
        if ($this->trace) {
            $e = $this->stopwatch?->start('register_commands', 'console');
        }

        parent::registerCommands();

        if ($this->trace) {
            $e->stop();
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function bootKernel(): void
    {
        parent::bootKernel();

        if (($requestStack = $this->getKernel()->getContainer()->get('request_stack')) instanceof DebugRequestStack) {
            $requestStack->pushCommand(hasRootTrace: $this->trace);
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function getDefaultInputDefinitionDefaults(): array
    {
        $definition = parent::getDefaultInputDefinition();
        $optionDefaults = $definition->getOptionDefaults();

        return array_merge(
            $definition->getArgumentDefaults(),
            array_combine(array_map(fn ($option) => '--'.$option, array_keys($optionDefaults)), $optionDefaults)
        );
    }
}
