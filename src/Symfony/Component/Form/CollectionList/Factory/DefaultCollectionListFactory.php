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

use Symfony\Component\Form\CollectionList\ArrayCollectionList;
use Symfony\Component\Form\CollectionList\LazyCollectionList;
use Symfony\Component\Form\CollectionList\Loader\CollectionLoaderInterface;

/**
 * Default implementation of {@link CollectionListFactoryInterface}.
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 * @author Jules Pietri <jules@heahprod.com>
 */
class DefaultCollectionListFactory implements CollectionListFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function createListFromEntries($entries, $name = null, $type = null)
    {
        return new ArrayCollectionList($entries, $name, $type);
    }

    /**
     * {@inheritdoc}
     */
    public function createListFromLoader(CollectionLoaderInterface $loader, $name = null, $type = null)
    {
        return new LazyCollectionList($loader, $name, $type);
    }
}
