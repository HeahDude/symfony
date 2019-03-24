<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Bundle\FrameworkBundle\DependencyInjection\Loader\Configurator;

use Symfony\Component\DependencyInjection\Loader\Configurator\AbstractExtensionConfigurator;
use Symfony\Component\DependencyInjection\Loader\Configurator\AbstractExtensionSectionConfigurator;
use Symfony\Component\DependencyInjection\Loader\Configurator\Traits\CanBeEnabledTrait;
use Symfony\Component\Workflow\MarkingStore\MethodMarkingStore;

final class WorkflowSectionConfigurator extends AbstractExtensionSectionConfigurator
{
    use CanBeEnabledTrait;

    public const NAMESPACE = 'framework';
    public const SECTION = 'workflows';

    private $name;
    private $markingStore = [];
    private $places = [];
    private $transitions = [];

    public function __construct(AbstractExtensionConfigurator $extension, string $name, $singleState = true)
    {
        parent::__construct($extension);

        $this->name = $name;
        $this->markingStore['type'] = 'method';

        if ($singleState) {
            $this->set('type', 'state_machine');
            $this->markingStore['arguments'][0] = false;
        } else {
            $this->set('type', 'workflow');
            $this->markingStore['arguments'][0] = true;
        }
    }

    public function __destruct()
    {
        $this
            ->set('marking_store', $this->markingStore)
            ->set('places', $this->places)
            ->set('transitions', $this->transitions)
        ;
        $this->config = [$this->name => $this->config];

        parent::__destruct();
    }

    public function auditTrail(bool $enable = true)
    {
        return $this->enable($enable, 'audit_trail');
    }

    public function markingStoreProperty(string $property)
    {
        if (isset($this->markingStore['service'])) {
            throw new \LogicException(sprintf('The property is meant to configure the "%s" but a service "%s" is defined.', MethodMarkingStore::class, $this->markingStore['service']));
        }

        $this->markingStore['arguments'][1] = $property;

        return $this;
    }

    public function markingStoreService(string $id)
    {
        $this->markingStore['service'] = $id;
        unset($this->markingStore['type'], $this->markingStore['arguments']);

        return $this;
    }

    /**
     * @param string|array $supported Depends on the support strategy, by default class names
     *
     * @return $this
     */
    public function supports($supported)
    {
        return $this->set('supports', $supported);
    }

    public function supportStrategy(string $strategy)
    {
        return $this->set('support_strategy', $strategy);
    }

    /**
     * @param string|array $marking One or more places
     *
     * @return $this
     */
    public function initialMarking($marking)
    {
        return $this->set('initial_places', $marking);
    }

    public function place($name, array $metadata = [])
    {
        $this->places[$name] = ['name' => $name, 'metadata' => $metadata];

        return $this;
    }

    public function transition(string $name, $from, $to, array $metadata = [])
    {
        $this->transitions[$name] = ['name' => $name, 'from' => $from, 'to' => $to, 'metadata' => $metadata];

        return $this;
    }

    public function guardedTransition(string $name, string $guard, $from, $to, array $metadata = [])
    {
        $this->transition($name, $from, $to, $metadata);
        $this->transitions[$name]['guard'] = $guard;
        
        return $this;
    }

    public function metadata(array $metadata)
    {
        return $this->set('metadata', $metadata);
    }
}
