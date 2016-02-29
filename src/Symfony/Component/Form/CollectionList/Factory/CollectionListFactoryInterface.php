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

/**
 * Creates {@link CollectionListInterface} instances.
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 * @author Jules Pietri <jules@heahprod.com>
 */
interface CollectionListFactoryInterface
{
    /**
     * Creates a collection list for the given entries.
     *
     * The entries should be passed in the values of the collection array.
     *
     * Optionally, some callable can be passed for generating the entry names and form types.
     * The callable receive the entry as first and the array key as the second
     * argument.
     *
     * For the $type defined as callable the second argument may be $name if not null,
     * original index otherwise.
     *
     * @param array|\Traversable   $entries The entries
     * @param null|callable        $name    The callable generating the entry
     *                                      names
     * @param null|callable|string $type    The callable generating the
     *                                      entries form type classes or
     *                                      the default form type class
     *
     * @return CollectionListInterface The collection list
     */
    public function createListFromEntries($entries, $name = null, $type = null);

    /**
     * Creates a collection list that is loaded with the given loader.
     *
     * Optionally, a callable can be passed for generating the entry names.
     * The callable receives the entry as first and the array key as the second
     * argument.
     *
     * @param CollectionLoaderInterface $loader The collection loader
     * @param null|callable             $name   The callable generating the entry
     *                                          names
     * @param null|callable|string      $type   The callable generating the
     *                                          entries form type classes or
     *                                          the default form type class
     *
     * @return CollectionListInterface The collection
     */
    public function createListFromLoader(CollectionLoaderInterface $loader, $name = null, $type = null);
}
