<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Routing\Loader\Configurator;

use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

/**
 * @author Nicolas Grekas <p@tchwork.com>
 */
class ImportConfigurator
{
    use Traits\RouteTrait;

    private $parent;
    private $imported;

    public function __construct(RouteCollection $parent, RouteCollection $imported)
    {
        $this->parent = $parent;
        $this->imported = $imported;
        $this->route = new Route('');
    }

    public function __destruct()
    {
        $importedCollection = new RouteCollection();

        foreach ($this->imported as $name => $imported) {
            $route = clone $this->route;
            $route->setPath($this->route->getPath().$imported->getPath());
            $route->addDefaults($imported->getDefaults());
            $route->addRequirements($imported->getRequirements());
            $route->addOptions($imported->getOptions());

            if ($specificHost = $imported->getHost()) {
                $route->setHost($specificHost);
            }
            if ($specificSchemes = $imported->getSchemes()) {
                $route->setSchemes($specificSchemes);
            }
            if ($specificMethods = $imported->getMethods()) {
                $route->setMethods($specificMethods);
            }
            if ($specificCondition= $imported->getCondition()) {
                $route->setCondition($specificCondition);
            }

            $importedCollection->add($name, $route);
        }

        $this->parent->addCollection($importedCollection);
    }

    /**
     * Sets the prefix to add to the path of all child routes.
     *
     * @param string $prefix
     *
     * @return $this
     */
    final public function prefix($prefix)
    {
        $this->route->setPath($prefix);

        return $this;
    }
}
