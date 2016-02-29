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

/**
 * A list of entries with arbitrary data types.
 *
 * The user of this class is responsible for assigning string names to the
 * entries and form types. The entries, their names and form types are all
 * passed to the constructor.
 * Each entry must have a corresponding name (with the same array key) in
 * the name array and the corresponding form type in the type array.
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 * @author Jules Pietri <jules@heahprod.com>
 */
class ArrayCollectionList implements CollectionListInterface
{
    /**
     * The entries of the collection.
     *
     * @var array
     */
    protected $entries;

    /**
     * The entry form type classes indexed by names.
     *
     * @var array
     */
    protected $types;

    /**
     * The names indexed by the original keys.
     *
     * @var array
     */
    protected $structuredNames;

    /**
     * The form type classes indexed by the original keys.
     *
     * @var array
     */
    protected $structuredTypes;

    /**
     * The original keys of the collection array.
     *
     * @var (int|string)[]
     */
    protected $originalIndexes;

    /**
     * The original collection.
     *
     * @var array|\Traversable
     */
    protected $originalCollection;

    /**
     * The callback for creating the name for an entry.
     *
     * @var callable
     */
    protected $nameCallback;

    /**
     * The callback for creating the form type class for an entry.
     *
     * @var callable
     */
    protected $typeCallback;

    /**
     * The default form type class for an entry.
     *
     * @var string
     */
    protected $typeDefault = self::ENTRY_DEFAULT_FORM_TYPE_CLASS;

    /**
     * Creates a list with the given entries, names and form types.
     *
     * The given collection must have the same array keys as the name array
     * and the form type array.
     *
     * @param array|\Traversable   $entries The dynamic collection
     * @param callable|null        $name    The callable for creating the name
     *                                      for an entry. If `null` is passed,
     *                                      incrementing integers are used as
     *                                      names
     * @param string|callable|null $type    The callable for creating the form
     *                                      type for an entry and name. If `null`
     *                                      is passed, falls back on default form
     *                                      type. A string will be the default for
     *                                      all entries.
     */
    public function __construct($entries, callable $name = null, callable $type = null)
    {
        if ($entries instanceof \Traversable) {
            $entries = iterator_to_array($entries);
        }

        if (null !== $name) {
            // If a deterministic name generator was passed, use it later
            $this->nameCallback = $name;
        } else {
            // Otherwise simply generate incrementing integers as index names
            $i = 0;
            $name = function () use (&$i) {
                return $i++;
            };
        }

        if (null === $type) {
            $this->typeDefault = $type;
        } elseif (is_callable($type)) {
            // If a deterministic form type class generator was passed, use it later
            $this->typeCallback = $type;
        } else {
            // If it is string replace default
            $this->typeDefault = (string) $type;
        }

        $this->flatten($entries, $name, $entriesByNames, $typesByNames, $keysByNames, $structuredNames, $structuredTypes);

        $this->types = $typesByNames;
        $this->entries = $entriesByNames;
        $this->originalIndexes = $keysByNames;
        $this->originalCollection = $entries;
        $this->structuredNames = $structuredNames;
        $this->structuredTypes = $structuredTypes;
    }

    /**
     * {@inheritdoc}
     */
    public function getEntries()
    {
        return $this->entries;
    }

    /**
     * {@inheritdoc}
     */
    public function getNames()
    {
        // Optimize memory as $this->entries might be heavy
        // check on $this->types which share the same keys.
        return array_map('strval', array_keys($this->types));
    }

    /**
     * {@inheritdoc}
     */
    public function getTypes()
    {
        return $this->types;
    }

    /**
     * {@inheritdoc}
     */
    public function getStructuredNames()
    {
        return $this->structuredNames;
    }

    /**
     * {@inheritdoc}
     */
    public function getStructuredTypes()
    {
        return $this->structuredTypes;
    }

    /**
     * {@inheritdoc}
     */
    public function getOriginalIndexes()
    {
        return $this->originalIndexes;
    }

    /**
     * {@inheritdoc}
     */
    public function getOriginalCollection()
    {
        return $this->originalCollection;
    }

    /**
     * {@inheritdoc}
     */
    public function getEntriesForNames(array $names)
    {
        $entries = array();

        foreach ($names as $i => $givenName) {
            // Memory optimization as $this->entries and $this->types
            // share the same names keys, check the lighter one first.
            if (array_key_exists($givenName, $this->types)) {
                $entries[$i] = $this->entries[$givenName];
            }
        }

        return $entries;
    }

    /**
     * {@inheritdoc}
     */
    public function getNamesForEntries(array $entries)
    {
        $names = array();

        // Use the name callback to compare entries by their names, if present
        if ($this->nameCallback) {
            $givenNames = array();
            foreach ($entries as $i => $givenEntry) {
                $givenNames[$i] = (string) call_user_func($this->nameCallback, $givenEntry);
            }
            return array_intersect($givenNames, $this->getNames());
        }

        // Otherwise compare entries by identity
        foreach ($entries as $i => $givenEntry) {
            foreach ($this->entries as $name => $entry) {
                if ($entry === $givenEntry) {
                    $names[$i] = (string) $name;

                    break;
                }
            }
        }

        return $names;
    }

    /**
     * {@inheritdoc}
     */
    public function getEntriesForTypes(array $types)
    {
        $names = $this->getNamesForTypes($types);

        return $this->getEntriesForNames($names);
    }

    /**
     * {@inheritdoc}
     */
    public function getTypesForEntries(array $entries)
    {
        $names = $this->getNamesForEntries($entries);

        return $this->getTypesForNames($names);
    }

    /**
     * {@inheritdoc}
     */
    public function getNamesForTypes(array $types)
    {
        $names = array();

        foreach ($types as $i => $givenType) {
            foreach ($this->types as $name => $type) {
                if ($givenType === $type) {
                    $names[$i] = $name;
                }
            }
        }

        return $names;
    }

    /**
     * {@inheritdoc}
     */
    public function getTypesForNames(array $names)
    {
        // Optimize performance if the collection is not polymorphic
        if (null === $this->typeCallback) {
            return array_fill_keys(array_keys($names), $this->typeDefault);
        }

        $types = array();
        $entries = $this->getEntriesForNames($names);

        foreach ($names as $i => $givenName) {
            // Generate the type with the callback
            $type = call_user_func($this->typeCallback, $entries[$i], $givenName);
            // If the callback return `void`, `null`, `false` or an empty string, use default
            $types[$i] = (string) $type ?: $this->typeDefault;
        }

        return array_intersect($types, array_keys($this->types));
    }

    /**
     * Flattens an array collection into the given output variables.
     *
     * @param array    $entries         The collection array to flatten
     * @param callable $name            The callable for generating entry form types
     * @param array    $entriesByNames  The flattened entries indexed by the
     *                                  corresponding names
     * @param array    $typesByNames    The form types indexed by the
     *                                  corresponding names
     * @param array    $keysByNames     The original keys indexed by the
     *                                  corresponding values
     * @param array    $structuredNames The entry names indexed by the
     *                                  original keys
     * @param array    $structuredTypes The entry form types indexed by the
     *                                  original keys
     *
     * @internal Must not be used by user-land code
     */
    protected function flatten(array $entries, $name, &$entriesByNames, &$typesByNames, &$keysByNames, &$structuredNames, &$structuredTypes)
    {
        if (null === $entriesByNames) {
            $entriesByNames = array();
            $typesByNames = array();
            $keysByNames = array();
            $structuredNames = array();
            $structuredTypes = array();
        }

        foreach ($entries as $key => $entry) {
            $entryName = (string) call_user_func($name, $entry);
            $entriesByNames[$entryName] = $entry;
            $keysByNames[$entryName] = $key;
            $structuredNames[$key] = $entryName;

            $type =  null === $this->typeCallback
                ? $this->typeDefault
                : (string) call_user_func($this->typeCallback, $entry, $entryName);

            $entryType = $type ?: $this->typeDefault;
            $typesByNames[$entryName] = $entryType;
            $structuredTypes[$key] = $entryType;
        }
    }
}
