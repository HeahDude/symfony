<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Form\CollectionList\Loader;

use Symfony\Component\Form\CollectionList\CollectionListInterface;

/**
 * Loads a collection list.
 *
 * The methods {@link loadEntriesForNames()} and {@link loadNamesForEntries()}
 * can be used to load the list only partially in cases where a fully-loaded
 * list is not necessary.
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 * @author Jules Pietri <jules@heahprod.com>
 */
interface CollectionLoaderInterface
{
    /**
     * Loads a list of entries.
     *
     * Optionally, a callable can be passed for generating the entry names.
     * The callable receives the entry as first and only argument.
     *
     * Also optionally, a default form type class or a callable generating form
     * type fully qualified class names can be passed.
     * The callable takes the entry as first argument and the name as second
     * argument.
     *
     * @param null|callable        $name The callable which generates the names
     *                                   from entries
     * @param null|callable|string $type A callable generating the entry fully
     *                                   qualified class names or the default
     *                                   form type class
     *
     * @return CollectionListInterface The loaded collection
     */
    public function loadCollectionList($name = null, $type = null);

    /**
     * Loads the entries corresponding to the given names.
     *
     * The entries are returned with the same keys and in the same order as the
     * corresponding names in the given array.
     *
     * Optionally, a callable can be passed for generating the name values.
     * The callable receives the entry as first and only argument.
     *
     * @param string[]      $names An array of entry names. Non-existing
     *                             names in this array are ignored
     * @param null|callable $name  The callable generating the entry names
     *
     * @return array A collection array
     */
    public function loadEntriesForNames(array $names, $name = null);

    /**
     * Loads the names corresponding to the given entries.
     *
     * The names are returned with the same keys and in the same order as the
     * corresponding entries in the given array.
     *
     * Optionally, a callable can be passed for generating the entry names.
     * The callable receives the entry as first and the array key as the second
     * argument.
     *
     * @param array         $entries An array of entries. Non-existing entries in
     *                               this array are ignored
     * @param null|callable $name    The callable generating the entry names
     *
     * @return string[] An array of entry names
     */
    public function loadNamesForEntries(array $entries, $name = null);

    /**
     * Loads the entries corresponding to the given form types class.
     *
     * The entries are returned in different arrays indexed by type and in the
     * same order as the corresponding types in the given array.
     *
     * Optionally, a callable can be passed for generating the form type class.
     * The callable receives the entry as first argument and its name as second
     * argument.
     *
     * @param string[]             $types An array of form types class. Non-existing
     *                                    types in this array are ignored
     * @param null|callable|string $type  A callable generating the entry fully qualified
     *                                    class names or the default form type class
     *
     * @return array A collection array
     */
    public function loadEntriesForTypes(array $types, $type = null);

    /**
     * Loads the form type classes corresponding to the given entries.
     *
     * The form types classes are returned with the same keys and in the same order
     * as the corresponding entries in the given array.
     *
     * Optionally, a callable can be passed for generating the entry names.
     * The callable receives the entry as first and the array key as the second
     * argument.
     *
     * @param array                $entries An array of entries. Non-existing entries in
     *                                      this array are ignored
     * @param null|callable|string $type    A callable generating the entry fully qualified
     *                                      class names or the default form type class
     *
     * @return string[] An array of entry names
     */
    public function loadTypesForEntries(array $entries, $type = null);

    /**
     * Loads the entry names corresponding to the given form types class.
     *
     * The entry names are returned with the same keys and in the same order
     * as the corresponding types in the given array.
     *
     * Optionally, a callable can be passed for generating the entry names.
     * The callable receives the entry as first argument and only argument.
     *
     * @param string[]      $types An array of form type classes. Non-existing
     *                             classes in this array are ignored
     * @param null|callable $name  The callable generating the entry names
     *
     * @return string[] An array of entry names
     */
    public function loadNamesForTypes(array $types, $name = null);

    /**
     * Loads the form type classes corresponding to the given entry names.
     *
     * The form types classes are returned with the same keys and in the
     * same order as the corresponding entry names in the given array.
     *
     * Optionally, a callable can be passed for generating the entry form type classes.
     * The callable receives the entry as first argument and its name as second argument.
     *
     * @param string[]             $names An array of entry names. Non-existing names in
     *                                    this array are ignored
     * @param null|callable|string $type  A callable generating the entry fully qualified
     *                                    class names or the default form type class
     *
     * @return string[] An array of entry names
     */
    public function loadTypesForNames(array $names, $type = null);
}
