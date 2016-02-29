<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Form\CollectionList;

use Symfony\Component\Form\CollectionList\Loader\CollectionLoaderInterface;

/**
 * A collection list that loads its entries lazily.
 *
 * The entries are fetched using a {@link CollectionLoaderInterface} instance.
 * If only {@link getEntriessForNames()} or {@link getNamesForEntries()} is
 * called, the collection list is only loaded partially for improved performance.
 *
 * Once {@link getEntries()} or {@link getNames()} is called, the list is
 * loaded fully.
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 * @author Jules Pietri <jules@heahprod.com>
 */
class LazyCollectionList implements CollectionListInterface
{
    /**
     * The collection loader.
     *
     * @var CollectionLoaderInterface
     */
    private $loader;

    /**
     * The callable creating string names for each entries.
     *
     * If null, indexes are incremented from 0.
     *
     * @var null|callable
     */
    private $name;

    /**
     * The default form type class or callable creating form type classes for each entries.
     *
     * If null, falls back on CollectionListInterface::ENTRY_DEFAULT_FORM_TYPE_CLASS.
     *
     * @var null|string|callable
     */
    private $type;

    /**
     * @var CollectionListInterface|null
     */
    private $loadedList;

    /**
     * Creates a lazily-loaded list using the given loader.
     *
     * Optionally, a callable can be passed for generating the entry names.
     * The callable receives the entry as first and only argument.
     *
     * Also optionally, a default form type class or a callable generating form
     * type fully qualified class names can be passed.
     * The callable takes the entry as first argument and the name as second.
     *
     * @param CollectionLoaderInterface $loader The collection loader
     * @param null|callable             $name   The callable generating the entry
     *                                          names
     * @param null|string|callable $type        The default form type class or callable
     *                                          generating the entry form type fully
     *                                          qualified class names
     */
    public function __construct(CollectionLoaderInterface $loader, callable $name = null, $type = null)
    {
        $this->loader = $loader;
        $this->name = $name;
        $this->type = $type;
    }

    /**
     * {@inheritdoc}
     */
    public function getEntries()
    {
        if (!$this->loadedList) {
            $this->loadedList = $this->loader->loadCollectionList($this->name, $this->type);
        }

        return $this->loadedList->getEntries();
    }

    /**
     * {@inheritdoc}
     */
    public function getNames()
    {
        if (!$this->loadedList) {
            $this->loadedList = $this->loader->loadCollectionList($this->name, $this->type);
        }

        return $this->loadedList->getNames();
    }

    /**
     * {@inheritdoc}
     */
    public function getTypes()
    {
        if (!$this->loadedList) {
            $this->loadedList = $this->loader->loadCollectionList($this->name, $this->type);
        }

        return $this->loadedList->getTypes();
    }

    /**
     * {@inheritdoc}
     */
    public function getStructuredNames()
    {
        if (!$this->loadedList) {
            $this->loadedList = $this->loader->loadCollectionList($this->name, $this->type);
        }

        return $this->loadedList->getStructuredNames();
    }

    /**
     * {@inheritdoc}
     */
    public function getStructuredTypes()
    {
        if (!$this->loadedList) {
            $this->loadedList = $this->loader->loadCollectionList($this->name, $this->type);
        }

        return $this->loadedList->getStructuredTypes();
    }

    /**
     * {@inheritdoc}
     */
    public function getOriginalIndexes()
    {
        if (!$this->loadedList) {
            $this->loadedList = $this->loader->loadCollectionList($this->name, $this->type);
        }

        return $this->loadedList->getOriginalIndexes();
    }

    /**
     * {@inheritdoc}
     */
    public function getOriginalCollection()
    {
        if (!$this->loadedList) {
            $this->loadedList = $this->loader->loadCollectionList($this->name, $this->type);
        }

        return $this->loadedList->getOriginalCollection();
    }

    /**
     * {@inheritdoc}
     */
    public function getEntriesForNames(array $names)
    {
        if (!$this->loadedList) {
            return $this->loader->loadEntriesForNames($names, $this->name);
        }

        return $this->loadedList->getEntriesForNames($names);
    }

    /**
     * {@inheritdoc}
     */
    public function getNamesForEntries(array $entries)
    {
        if (!$this->loadedList) {
            return $this->loader->loadNamesForEntries($entries, $this->name);
        }

        return $this->loadedList->getNamesForEntries($entries);
    }

    /**
     * {@inheritdoc}
     */
    public function getEntriesForTypes(array $types)
    {
        if (!$this->loadedList) {
            return $this->loader->loadEntriesForTypes($types, $this->type);
        }

        return $this->loadedList->getEntriesForTypes($types);
    }

    /**
     * {@inheritdoc}
     */
    public function getTypesForEntries(array $entries)
    {
        if (!$this->loadedList) {
            return $this->loader->loadTypesForEntries($entries, $this->type);
        }

        return $this->loadedList->getTypesForEntries($entries);
    }

    /**
     * {@inheritdoc}
     */
    public function getNamesForTypes(array $types)
    {
        if (!$this->loadedList) {
            return $this->loader->loadNamesForTypes($types, $this->type);
        }

        return $this->loadedList->getNamesForTypes($types);
    }

    /**
     * {@inheritdoc}
     */
    public function getTypesForNames(array $names)
    {
        if (!$this->loadedList) {
            return $this->loader->loadTypesForNames($names, $this->name);
        }

        return $this->loadedList->getTypesForNames($names);
    }
}
