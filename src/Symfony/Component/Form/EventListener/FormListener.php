<?php

namespace Symfony\Component\Form\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Attribute\Form;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpKernel\Event\ControllerArgumentsEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class FormListener implements EventSubscriberInterface
{
    public function __construct(
        private readonly FormFactoryInterface $formFactory
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER_ARGUMENTS => ['onKernelControllerArgumentsEvent', -10],
        ];
    }

    public function onKernelControllerArgumentsEvent(ControllerArgumentsEvent $event)
    {
        $request = $event->getRequest();
        $namedArguments = $event->getNamedArguments();

        foreach ($namedArguments as $name => $argument) {
            if ($argument instanceof FormInterface) {
                /** @var Form $attribute */
                $attribute = $request->attributes->get('_form_'.$name);
                $data = null;

                if ($attribute->data instanceof ControllerArgument) {
                    $data = $namedArguments[$attribute->data];
                } elseif ($attribute->data instanceof RequestAttribute) {
                    $data = $request->attributes->get($attribute->data);
                } elseif ($attribute->autoMapData && $dataClass = $argument->getConfig()->getDataClass()) {
                    $data = current(array_filter($namedArguments, fn ($arg) => is_a($arg, $dataClass, allow_string: true))) ?: null;
                }

                if (null !== $data) {
                    // recreate the form to allow $builder->getData() and $options['data'] to be set as expected
                    if ($attribute->name) {
                        $argument = $this->formFactory->createNamed($attribute->name, $attribute->type, $data, $attribute->options);
                    } else {
                        $argument = $this->formFactory->create($attribute->type, $data, $attribute->options);
                    }

                    $namedArguments[$name] = $argument;
                    $event->setArguments(array_values($namedArguments));
                }

                $argument->handleRequest($request);
            }
        }
    }
}
