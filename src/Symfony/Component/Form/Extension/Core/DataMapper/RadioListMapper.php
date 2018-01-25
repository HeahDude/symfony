<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Form\Extension\Core\DataMapper;

use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Exception\UnexpectedTypeException;

/**
 * Maps choices to/from radio forms.
 *
 * A {@link ChoiceListInterface} implementation is used to find the
 * corresponding string values for the choices. The radio form whose "value"
 * option corresponds to the selected value is marked as selected.
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class RadioListMapper implements DataMapperInterface
{
    /**
     * {@inheritdoc}
     */
    public function mapDataToForms($data, $forms)
    {
        if (!is_string($data)) {
            throw new UnexpectedTypeException($data, 'string');
        }

        foreach ($forms as $radio) {
            $radio->setData($data === $radio->getConfig()->getOption('value'));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function mapFormsToData($forms, &$data)
    {
        if (null !== $data && !is_string($data)) {
            throw new UnexpectedTypeException($data, 'null or string');
        }

        foreach ($forms as $radio) {
            if ($radio->getData()) {
                if ('placeholder' === $radio->getName()) {
                    return;
                }

                $data = $radio->getConfig()->getOption('value');

                return;
            }
        }

        $data = null;
    }
}
