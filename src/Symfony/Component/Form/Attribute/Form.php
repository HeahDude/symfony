<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Form\Attribute;

use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormTypeInterface;

/**
 * @author Jules Pietri <jules@heahprod.com>
 */
#[\Attribute(\Attribute::TARGET_PARAMETER)]
class Form
{
    public function __construct(
        /**
         * @var class-string<FormTypeInterface>
         */
        public string $type,

        /**
         * Name of the form (optional).
         * Is set {@see FormFactoryInterface::createNamed()} is used.
         */
        public ?string $name = null,

        public array $options = [],

        public RequestAttribute|ControllerArgument|null $data = null,

        /**
         * If true the data is set if a request attribute or a controller
         * argument meets the form data class type.
         */
        public bool $autoMapData = true
    ) {
    }
}
