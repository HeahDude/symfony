<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Bundle\TwigBundle\DependencyInjection\Loader\Configurator;

use Symfony\Component\DependencyInjection\Loader\Configurator\AbstractExtensionConfigurator;

class TwigExtensionConfigurator extends AbstractExtensionConfigurator
{
    public const NAMESPACE = 'twig';

    private $date = [];
    private $numberFormat = [];

    public function __destruct()
    {
        $this->set('date', $this->date);
        $this->set('number_format', $this->numberFormat);

        parent::__destruct();
    }

    final public function exceptionController(string $controller)
    {
        return self::set('exception_controller', $controller);
    }

    final public function defaultPath(array $path)
    {
        return self::set('default_path', $path);
    }

    final public function paths(array $paths)
    {
        return self::set('paths', $paths);
    }

    final public function globals(array $globals)
    {
        return self::set('globals', $globals);
    }

    final public function formThemes(array $themes)
    {
        return self::set('form_themes', $themes);
    }

    final public function autoescape($autoescape)
    {
        return self::set('autoescape', $autoescape);
    }

    final public function autoescapeService(string $service)
    {
        return self::set('autoescape_service', $service);
    }

    final public function autoescapeServiceMethod(string $method)
    {
        return self::set('autoescape_service_method', $method);
    }

    final public function baseTemplateClass(string $class)
    {
        return self::set('base_template_class', $class);
    }

    final public function cache(string $path)
    {
        return self::set('cache', $path);
    }

    final public function charset(string $charset)
    {
        return self::set('charset', $charset);
    }

    final public function debug(bool $debug = true)
    {
        return self::set('debug', $debug);
    }

    final public function strictVariables(bool $strict = true)
    {
        return self::set('strictVariables', $strict);
    }

    final public function autoReload($autoReload)
    {
        return self::set('auto_reload', $autoReload);
    }

    final public function optimizations(int $optimizations)
    {
        return self::set('optimizations', $optimizations);
    }

    final public function dateFormat(string $format)
    {
        $this->date['format'] = $format;

        return $this;
    }

    final public function dateIntervalFormat(string $format)
    {
        $this->date['interval_format'] = $format;

        return $this;
    }

    final public function timezoneFormat(string $format)
    {
        $this->date['timezone'] = $format;

        return $this;
    }

    final public function numberDecimals(int $decimals)
    {
        $this->numberFormat['decimals'] = $decimals;

        return $this;
    }

    final public function numberDecimalPoint(string $point)
    {
        $this->numberFormat['decimal_point'] = $point;

        return $this;
    }

    final public function numberThousandSeparator(string $separator)
    {
        $this->numberFormat['thousands_separator'] = $separator;

        return $this;
    }
}
