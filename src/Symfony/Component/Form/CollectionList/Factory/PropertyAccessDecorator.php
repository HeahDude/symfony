<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Form\CollectionList\Factory;

use Symfony\Component\Form\CollectionList\CollectionListInterface;
use Symfony\Component\Form\CollectionList\Loader\CollectionLoaderInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\PropertyAccess\PropertyPath;

/**
 * Adds property path support to a collection list factory.
 *
 * Pass the decorated factory to the constructor:
 *
 * ```php
 * $decorator = new PropertyAccessDecorator($factory);
 * ```
 *
 * You can now pass property paths for generating entry indexes
 * and form types:
 *
 * ```php
 * // extract names from the $name property
 * $list = $createListFromEntries($collection, $name, $type);
 * ```
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 * @author Jules Pietri <jules@heahprod.com>
 */
class PropertyAccessDecorator implements CollectionListFactoryInterface
{
    /**
     * @var CollectionListFactoryInterface
     */
    private $decoratedFactory;

    /**
     * @var PropertyAccessorInterface
     */
    private $propertyAccessor;

    /**
     * Decorates the given factory.
     *
     * @param CollectionListFactoryInterface $decoratedFactory The decorated factory
     * @param null|PropertyAccessorInterface $propertyAccessor The used property accessor
     */
    public function __construct(CollectionListFactoryInterface $decoratedFactory, PropertyAccessorInterface $propertyAccessor = null)
    {
        $this->decoratedFactory = $decoratedFactory;
        $this->propertyAccessor = $propertyAccessor ?: PropertyAccess::createPropertyAccessor();
    }

    /**
     * Returns the decorated factory.
     *
     * @return CollectionListFactoryInterface The decorated factory
     */
    public function getDecoratedFactory()
    {
        return $this->decoratedFactory;
    }

    /**
     * {@inheritdoc}
     *
     * @param array|\Traversable                $entries A collection array
     * @param null|callable|string|PropertyPath $name    The callable or path for
     *                                                   generating the entries names
     * @param null|callable|string              $type    The callable generating the
     *                                                   entries form type classes or
     *                                                   the default form type class
     *
     * @return CollectionListInterface The collection list
     */
    public function createListFromEntries($entries, $name = null, $type = null)
    {
        if (is_string($name) && !is_callable($name)) {
            $name = new PropertyPath($name);
        }

        if ($name instanceof PropertyPath) {
            $accessor = $this->propertyAccessor;
            $name = function ($entry) use ($accessor, $name) {
                // The callable may be invoked with a non-object/array value
                // when such values are passed to
                // CollectionListInterface::getNamesForEntries(). Handle this case
                // so that the call to getValue() doesn't break.
                if (is_object($entry) || is_array($entry)) {
                    return $accessor->getValue($entry, $name);
                }
            };
        }

        return $this->decoratedFactory->createListFromEntries($entries, $name, $type);
    }

    /**
     * {@inheritdoc}
     *
     * @param CollectionLoaderInterface         $loader The collection loader
     * @param null|callable|string|PropertyPath $name   The callable or path for
     *                                                  generating the entry names
     * @param null|callable|string              $type   The callable or path for
     *                                                  generating the entry form
     *                                                  type classes or the default
     *                                                  form type class
     *
     * @return CollectionListInterface The collection list
     */
    public function createListFromLoader(CollectionLoaderInterface $loader, $name = null, $type = null)
    {
        if (is_string($name) && !is_callable($name)) {
            $name = new PropertyPath($name);
        }

        if ($name instanceof PropertyPath) {
            $accessor = $this->propertyAccessor;
            $name = function ($entry) use ($accessor, $name) {
                // The callable may be invoked with a non-object/array value
                // when such values are passed to
                // CollectionListInterface::getNamesForEntries(). Handle this case
                // so that the call to getValue() doesn't break.
                if (is_object($entry) || is_array($entry)) {
                    return $accessor->getValue($entry, $name);
                }
            };
        }

        return $this->decoratedFactory->createListFromLoader($loader, $name, $type);
    }
}
