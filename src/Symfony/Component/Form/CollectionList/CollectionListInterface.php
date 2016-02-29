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
 * A list of entries that can be edited in a collection field.
 *
 * A collection list assigns unique string names and form type full qualified class
 * names to each of a list of entries.
 * These string names are displayed as part of the "name" attributes in HTML and
 * submitted back to the server, they serve as internal indexes.
 * The form type classes will be used to add each entry in a form.
 *
 * The acceptable data types for the entries depend on the implementation.
 * Names must always be strings and (within the list) free of duplicates.
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 * @author Jules Pietri <jules@heahprod.com>
 */
interface CollectionListInterface
{
    const ENTRY_DEFAULT_FORM_TYPE_CLASS = 'Symfony\Component\Form\Extension\Core\Type\TextType';

    /**
     * Returns all entries of the collection.
     *
     * @return array The entries indexed by the corresponding names
     */
    public function getEntries();

    /**
     * Returns the keys of the types property.
     *
     * The names are strings that do not contain duplicates.
     *
     * @return string[] The entry names
     */
    public function getNames();

    /**
     * Return the entry form type classes indexed by entry names.
     *
     * The values are strings corresponding to form type full qualified
     * class names indexed by names.
     *
     * @return string[] The full qualified class names of the form types
     *                  by name of entries in the collection list
     */
    public function getTypes();

    /**
     * Returns the entry names in the structure originally passed to the list.
     *
     * Contrary to {@link getNames()}, the result is indexed by the original
     * keys of the entries:
     *
     *     $form->add('tickets', CollectionType::class, array(
     *         'entry_type_map' => function ($entry, $index) {
     *             $class = explode('\\', get_class($entry));
     *
     *             return '\AppBundle\Form\'.ucfirst(end($class)).'Type';
     *         },
     *         'entry_index' => function ($entry) {
     *             return strtolower($entry->getNamespacedNumber())
     *         },
     *         'data' => $tickets,
     *     ));
     *
     * In this example, if the data `Ticket` entities are indexed by ids,
     * considering a merge with two identical ids, the result of this method is:
     *
     *     array(
     *         '45' => 'acme-test_45',
     *         '46' => 'acme-test_46',
     *         '0' => 'acme-other-test_45',
     *     )
     *
     * @return string[] The entry names
     */
    public function getStructuredNames();

    /**
     * Returns the form type classes in the structure originally passed to the list.
     *
     * Classes are full qualified class names.
     *
     * Contrary to {@link getTypes()}, the result is indexed by the original
     * keys of the entries.
     *
     * @return string[] The entry form type full qualified class names
     */
    public function getStructuredTypes();

    /**
     * Returns the original collection indexes of the entries.
     *
     * The indexes are the keys of the entries that was passed in the
     * original collection.
     *
     * @return (string|int)[] The original entry keys indexed by the
     *                        corresponding entry names
     */
    public function getOriginalIndexes();

    /**
     * Returns the original collection.
     *
     * @return array|\Traversable The original collection
     */
    public function getOriginalCollection();

    /**
     * Returns the entries corresponding to the given names.
     *
     * The entries are returned with the same keys and in the same order as
     * the corresponding names in the given array.
     *
     * @param string[] $names An array of entry names. Non-existing names in
     *                        this array are ignored
     *
     * todo doctrine bridge, A \Doctrine\Common\Collections\Collection should be returned if needed.
     * @return array A collection array
     */
    public function getEntriesForNames(array $names);

    /**
     * Returns the names corresponding to the given entries.
     *
     * The names are returned with the same keys and in the same order as
     * the corresponding entries in the given array.
     *
     * @param array $entries A collection array. Non-existing entries in this
     *                                           array are ignored
     *
     * @return string[] An array of entry names
     */
    public function getNamesForEntries(array $entries);

    /**
     * Returns the entries corresponding to the given form types classes.
     *
     * The entries are returned with the same keys and in the same order as the
     * corresponding classes in the given array.
     *
     * Classes has to be full qualified class names.
     *
     * @param string[] $types An array of entry form type classes. Non-existing
     *                        classes in this array are ignored
     *
     * todo doctrine bridge, A \Doctrine\Common\Collections\Collection should be returned if needed.
     * @return array A collection array
     */
    public function getEntriesForTypes(array $types);

    /**
     * Returns the form type classes corresponding to the given entries.
     *
     * The full qualified class names are returned with the same keys and in
     * the same order as the corresponding entries in the given array.
     *
     * @param array $entries An array of entries. Non-existing entries in
     *                       this array are ignored
     *
     * @return string[] An array of form type full qualified class names
     */
    public function getTypesForEntries(array $entries);

    /**
     * Returns the entry names corresponding to the given form types classes.
     *
     * The entry names are returned with the same keys and in the same order as the
     * corresponding classes in the given array.
     *
     * Classes has to be full qualified class names.
     *
     * @param string[] $types An array of entry names. Non-existing
     *                        classes in this array are ignored
     *
     * @return string[] An array of entry names
     */
    public function getNamesForTypes(array $types);

    /**
     * Returns the form type classes corresponding to the given entry names.
     *
     * The full qualified class names are returned with the same keys and in
     * the same order as the corresponding entry names in the given array.
     *
     * @param string[] $names An array of form type classes. Non-existing entry
     *                        names in this array are ignored
     *
     * @return string[] An array of form type full qualified class names
     */
    public function getTypesForNames(array $names);
}
