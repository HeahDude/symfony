<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Bundle\FrameworkBundle\DataCollector;

use Symfony\Bundle\FrameworkBundle\Console\DebugApplication;
use Symfony\Component\Console\Command\TraceableCommand;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;
use Symfony\Component\HttpKernel\Debug\CliRequest;
use Symfony\Component\VarDumper\Cloner\Data;

class CommandDataCollector extends DataCollector
{
    /**
     * {@inheritdoc}
     */
    public function collect(Request $request, Response $response, \Throwable $exception = null)
    {
        if (!$request instanceof CliRequest) {
            return;
        }

        $command = $request->command;
        $application = $command->getApplication();

        $this->data['command'] = [
            'exit_code' => $command->exitCode,
            'application_name' => $application->getName(),
            'application_version' => $application->getVersion(),
            'full_representation' => $request->getUri(),
            'instance' => $this->cloneVar($command->getCommand()),
            'memory_usage' => $command->getMemoryUsage(),
            'duration' => $command->getDuration(),
            'enabled' => $command->isEnabled(),
            'visible' => !$command->isHidden(),
            'interactive' => $command->isInteractive,
            'validate_input' => !$command->ignoreValidation,
            'arguments' => array_map(
                fn ($value) => $this->cloneVar($value),
                $command->arguments
            ),
            'options' => array_map(
                fn ($value) => $this->cloneVar($value),
                $command->options
            ),
            'interactive_inputs' => array_map(
                fn ($value) => $this->cloneVar($value),
                self::extractInteractiveInputs($command)
            ),
            'input' => $this->cloneVar($command->input),
            'output' => $this->cloneVar($command->output),
            'verbosity_level' => $command->verbosityLevel,
            'helper_set' => array_map(
                fn (object $helper) => $this->cloneVar($helper),
                \iterator_to_array($command->getHelperSet())
            ),
            'server' => array_map(
                fn ($value) => $this->cloneVar($value),
                $request->server->all()
            ),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'command';
    }

    /**
     * {@inheritdoc}
     */
    public function reset()
    {
        $this->data = [];
    }

    public function getExitCode(): ?int
    {
        return $this->data['command']['exit_code'] ?? null;
    }

    public function getApplicationName(): ?string
    {
        return $this->data['command']['application_name'] ?? null;
    }

    public function getApplicationVersion(): ?string
    {
        return $this->data['command']['application_version'] ?? null;
    }

    public function getCommandFullRepresentation(): ?string
    {
        return $this->data['command']['full_representation'] ?? null;
    }

    public function getCommand(): ?Data
    {
        return $this->data['command']['instance'] ?? null;
    }

    public function getMemoryUsage(): ?string
    {
        return $this->data['command']['memory_usage'] ?? null;
    }

    public function getDuration(): ?string
    {
        return $this->data['command']['duration'] ?? null;
    }

    public function isEnabled(): ?bool
    {
        return $this->data['command']['enabled'] ?? null;
    }

    public function isVisible(): ?bool
    {
        return $this->data['command']['visible'] ?? null;
    }

    public function isInteractive(): ?bool
    {
        return $this->data['command']['interactive'] ?? null;
    }

    public function validateInput(): ?bool
    {
        return $this->data['command']['validate_input'] ?? null;
    }

    /**
     * @return array<string, mixed>
     */
    public function getArguments(): array
    {
        return $this->data['command']['arguments'] ?? [];
    }

    /**
     * @return array<string, mixed>
     */
    public function getOptions(): array
    {
        return $this->data['command']['options'] ?? [];
    }

    /**
     * @return array<string, mixed>
     */
    public function getInteractiveInputs(): array
    {
        return $this->data['command']['interactive_inputs'] ?? [];
    }

    public function getInput(): ?Data
    {
        return $this->data['command']['input'] ?? null;
    }

    public function getOutput(): ?Data
    {
        return $this->data['command']['output'] ?? null;
    }

    public function getVerbosityLevel(): ?string
    {
        return match ($this->data['command']['verbosity_level'] ?? null) {
            OutputInterface::VERBOSITY_QUIET => 'quiet',
            OutputInterface::VERBOSITY_NORMAL => 'normal',
            OutputInterface::VERBOSITY_VERBOSE => 'verbose',
            OutputInterface::VERBOSITY_VERY_VERBOSE => 'very verbose',
            OutputInterface::VERBOSITY_DEBUG => 'debug',
            default => null,
        };
    }

    /**
     * @return array<string, class-string>
     */
    public function getHelperSet(): array
    {
        return $this->data['command']['helper_set'] ?? [];
    }

    /**
     * @return array<string, mixed>
     */
    public function getServer(): array
    {
        return $this->data['command']['server'] ?? [];
    }

    private static function extractInteractiveInputs(TraceableCommand $command): array
    {
        $application = $command->getApplication();
        $defaultDefinition = [];

        if ($application instanceof DebugApplication) {
            $defaultDefinition = $application->getDefaultInputDefinitionDefaults();
        }

        return \array_diff_key($command->interactiveInputs, $defaultDefinition, ['--env' => false, '--no-debug' => false]);
    }
}
