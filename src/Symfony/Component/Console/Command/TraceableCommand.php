<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Console\Command;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Completion\CompletionInput;
use Symfony\Component\Console\Completion\CompletionSuggestions;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Stopwatch\Stopwatch;

class TraceableCommand extends Command
{
    public ?int $exitCode = null;
    /** @var array<string, mixed> */
    public array $arguments;
    /** @var array<string, mixed> */
    public array $options;
    /** @var array<string, mixed> */
    public array $interactiveInputs = [];
    public InputInterface $input;
    public OutputInterface $output;
    public int $verbosityLevel;
    public bool $isInteractive;
    public bool $ignoreValidation = false;
    private ?string $fullRepresentation = null;

    public function __construct(
        private Command $command,
        private Stopwatch $stopwatch,
    ) {
        parent::__construct($this->command->getName());
        parent::setDefinition($this->command->getNativeDefinition());
    }

    /**
     * {@inheritdoc}
     */
    public function ignoreValidationErrors()
    {
        $this->ignoreValidation = true;
        $this->command->ignoreValidationErrors();
    }

    /**
     * {@inheritdoc}
     */
    public function setApplication(Application $application = null)
    {
        $this->command->setApplication($application);
    }

    /**
     * {@inheritdoc}
     */
    public function setHelperSet(HelperSet $helperSet)
    {
        $this->command->setHelperSet($helperSet);
    }

    /**
     * {@inheritdoc}
     */
    public function getHelperSet(): ?HelperSet
    {
        return $this->command->getHelperSet();
    }

    /**
     * {@inheritdoc}
     */
    public function getApplication(): ?Application
    {
        return $this->command->getApplication();
    }

    /**
     * {@inheritdoc}
     */
    public function isEnabled(): bool
    {
        return $this->command->isEnabled();
    }

    /**
     * {@inheritdoc}
     */
    public function run(InputInterface $input, OutputInterface $output): int
    {
        $this->arguments = $input->getArguments();
        $this->options = $input->getOptions();

        $this->stopwatch->start($this->getName(), 'command');

        try {
            $this->exitCode = $this->command->run($input, $output);
        } finally {
            try {
                $this->stopwatch->stop($this->getName());
            } catch (\LogicException) {
                // noop
            }
            $this->input = $input;
            $this->output = $output;
            $this->verbosityLevel = $output->getVerbosity();
            $this->isInteractive = $input->isInteractive();
            $this->extractInteractiveInputs($input->getArguments(), $input->getOptions());
        }

        return $this->exitCode;
    }

    /**
     * {@inheritdoc}
     */
    public function complete(CompletionInput $input, CompletionSuggestions $suggestions): void
    {
        $this->command->complete($input, $suggestions);
    }

    /**
     * {@inheritdoc}
     */
    public function setCode(callable $code): static
    {
        $this->command->setCode($code);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setDefinition(InputDefinition|array $definition): static
    {
        $this->command->setDefinition($definition);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getDefinition(): InputDefinition
    {
        return parent::getDefinition();
    }

    /**
     * {@inheritdoc}
     */
    public function getNativeDefinition(): InputDefinition
    {
        return $this->command->getNativeDefinition();
    }

    /**
     * {@inheritdoc}
     */
    public function addArgument(string $name, int $mode = null, string $description = '', mixed $default = null, array|\Closure $suggestedValues = null): static
    {
        $this->command->addArgument($name, $mode, $description, $default, $suggestedValues);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addOption(string $name, array|string $shortcut = null, int $mode = null, string $description = '', mixed $default = null, array|\Closure $suggestedValues = []): static
    {
        $this->command->addOption($name, $shortcut, $mode, $description, $default, $suggestedValues);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setName(string $name): static
    {
        $this->command->setName($name);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setProcessTitle(string $title): static
    {
        $this->command->setProcessTitle($title);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): ?string
    {
        return $this->command->getName();
    }

    /**
     * {@inheritdoc}
     */
    public function setHidden(bool $hidden = true): static
    {
        $this->command->setHidden($hidden);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function isHidden(): bool
    {
        return $this->command->isHidden();
    }

    /**
     * {@inheritdoc}
     */
    public function setDescription(string $description): static
    {
        $this->command->setDescription($description);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription(): string
    {
        return $this->command->getDescription();
    }

    /**
     * {@inheritdoc}
     */
    public function setHelp(string $help): static
    {
        $this->command->setHelp($help);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getHelp(): string
    {
        return $this->command->getHelp();
    }

    /**
     * {@inheritdoc}
     */
    public function getProcessedHelp(): string
    {
        return $this->command->getProcessedHelp();
    }

    /**
     * {@inheritdoc}
     */
    public function setAliases(iterable $aliases): static
    {
        $this->command->setAliases($aliases);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getAliases(): array
    {
        return $this->command->getAliases();
    }

    /**
     * {@inheritdoc}
     */
    public function getSynopsis(bool $short = false): string
    {
        return $this->command->getSynopsis($short);
    }

    /**
     * {@inheritdoc}
     */
    public function addUsage(string $usage): static
    {
        $this->command->addUsage($usage);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getUsages(): array
    {
        return $this->command->getUsages();
    }

    /**
     * {@inheritdoc}
     */
    public function getHelper(string $name): mixed
    {
        return $this->command->getHelper($name);
    }

    public function getCommand(): Command
    {
        return $this->command instanceof LazyCommand ? $this->command->getCommand() : $this->command;
    }

    public function getFullRepresentation(): string
    {
        if ($this->fullRepresentation) {
            // this method is called twice on saving the profile
            return $this->fullRepresentation;
        }

        $definition = $this->command->getDefinition();

        $input = '';
        foreach ($this->input->getArguments() as $argument => $value) {
            if (null !== $value
                && 'command' !== $argument
                && $value !== $definition->getArgument($argument)->getDefault()
            ) {
                $input .= sprintf(' "%s"', $value);
            }
        }
        foreach ($this->input->getOptions() as $option => $value) {
            if ($value !== $definition->getOption($option)->getDefault()) {
                if (null === $value && !$definition->getOption($option)->acceptValue()) {
                    $input .= sprintf(' --%s', $option);
                } elseif ('verbose' === $option) {
                    $input .= match ($this->output->getVerbosity()) {
                        OutputInterface::VERBOSITY_QUIET => '--quiet',
                        OutputInterface::VERBOSITY_VERBOSE => '-v',
                        OutputInterface::VERBOSITY_VERY_VERBOSE => '-vv',
                        OutputInterface::VERBOSITY_DEBUG => '-vvv',
                        default => '',
                    };
                } else {
                    $input .= sprintf(' --%s="%s"', $option, $value);
                }
            }
        }

        return $this->fullRepresentation = sprintf('%s %s', $this->getName(), trim($input));
    }

    public function getDuration(): string
    {
        try {
            return $this->stopwatch->getSectionEvents('_cli')[$this->getName()]?->getDuration().' ms';
        } catch (\LogicException) {
            return '';
        }
    }

    public function getMemoryUsage(): string
    {
        try {
            return sprintf('%.2F MiB', $this->stopwatch->getSectionEvents('_cli')[$this->getName()]?->getMemory() / 1024 / 1024);
        } catch (\LogicException) {
            return '';
        }
    }

    private function extractInteractiveInputs(array $arguments, array $options): void
    {
        foreach ($this->arguments as $name => $argument) {
            if ($arguments[$name] !== $argument) {
                $this->interactiveInputs[$name] = $arguments[$name];
            }
        }
        foreach (\array_diff_key($arguments, $this->arguments) as $name => $argument) {
            $this->interactiveInputs[$name] = $argument;
        }
        foreach ($this->options as $name => $option) {
            if ($options[$name] !== $option) {
                $this->interactiveInputs['--'.$name] = $options[$name];
            }
        }
        foreach (\array_diff_key($options, $this->options) as $name => $option) {
            $this->interactiveInputs['--'.$name] = $option;
        }
    }
}
