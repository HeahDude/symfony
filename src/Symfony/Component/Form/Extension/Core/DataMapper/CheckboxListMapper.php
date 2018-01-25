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
 * Maps choices to/from checkbox forms.
 *
 * A {@link ChoiceListInterface} implementation is used to find the
 * corresponding string values for the choices. Each checkbox form whose "value"
 * option corresponds to any of the selected values is marked as selected.
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class CheckboxListMapper implements DataMapperInterface
{
    /**
     * {@inheritdoc}
     */
    public function mapDataToForms($data, $forms)
    {
        if (null === $data) {
            return;
        }

        if (!is_array($data)) {
            throw new UnexpectedTypeException($data, 'array');
        }

        foreach ($forms as $checkbox) {
            $checkbox->setData(in_array($checkbox->getConfig()->getOption('value'), $data, true));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function mapFormsToData($forms, &$data)
    {
        if (!is_array($data)) {
            throw new UnexpectedTypeException($data, 'array');
        }

        $values = array();

        foreach ($forms as $checkbox) {
            if ($checkbox->getData()) {
                // construct an array of choice values
                $values[] = $checkbox->getConfig()->getOption('value');
            }
        }

        $data = $values;
    }
}
