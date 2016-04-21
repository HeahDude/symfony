<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\ValueExporter\Exporter;

use Symfony\Component\ValueExporter\Exception\InvalidFormatterException;
use Symfony\Component\ValueExporter\Formatter\ExpandedFormatterTrait;
use Symfony\Component\ValueExporter\Formatter\FormatterInterface;

/**
 * ValueExporterInterface implementations export PHP values.
 *
 * @author Jules Pietri <jules@heahprod.com>
 */
abstract class AbstractValueExporter implements ValueExporterInterface
{
    /**
     * @var int
     */
    protected $depth;
    /**
     * @var bool
     */
    protected $expand;

    /**
     * The supported formatter interface.
     *
     * @var string
     */
    protected $formatterInterface = FormatterInterface::class;

    /**
     * An array of arrays of formatters by priority.
     *
     * @var array[]
     */
    private $formatters = array();

    /**
     * A sorted array of formatters.
     *
     * @var FormatterInterface[]
     */
    private $sortedFormatters;

    /**
     * Takes {@link FormatterInterface} as arguments.
     *
     * They will be called in the given order.
     */
    final public function __construct()
    {
        $this->addFormatters(func_get_args());
    }

    /**
     * {@inheritdoc}
     */
    final public function addFormatters(array $formatters)
    {
        $this->sortedFormatters = null;

        foreach ($formatters as $formatter) {
            if (is_array($formatter)) {
                $priority = (int) $formatter[1];
                $formatter = $formatter[0];
            } else {
                $priority = 0;
            }

            $formatterClass = get_class($formatter);

            if (!$formatter instanceof $this->formatterInterface) {
                throw new InvalidFormatterException($formatterClass, static::class, $this->formatterInterface);
            }

            if (in_array(ExpandedFormatterTrait::class, class_uses($formatterClass), true)) {
                $formatter->setExporter($this);
            }

            // Using the class as key prevents duplicate
            $this->formatters[$priority][$formatterClass] = $formatter;
        }
    }

    /**
     * @return FormatterInterface[]
     */
    final protected function formatters()
    {
        if (null === $this->sortedFormatters) {
            krsort($this->formatters);
            $this->sortedFormatters = call_user_func_array('array_merge', $this->formatters);
        }

        return $this->sortedFormatters;
    }
}
