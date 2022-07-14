<?php

namespace Symfony\Component\Form\Controller;

use Symfony\Component\Form\Attribute\Form;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class FormArgumentValueResolver implements ArgumentValueResolverInterface
{
    public function __construct(
        private readonly FormFactoryInterface $formFactory
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        return is_a($argument->getType(), FormInterface::class, allow_string: true);
    }

    /**
     * {@inheritdoc}
     *
     * @return iterable<FormInterface>
     */
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $attribute = $argument->getAttributesOfType(Form::class, ArgumentMetadata::IS_INSTANCEOF)[0] ?? new Form(FormType::class);

        $request->attributes->set('_form_'.$argument->getName(), $attribute);

        // todo resolve options (as request attributes, as arguments) ?
        if (null !== $name = $attribute->name) {
            yield $this->formFactory->createNamed($name, $attribute->type, null, $attribute->options);
        } else {
            yield $this->formFactory->create($attribute->type, null, $attribute->options);
        }
    }
}
