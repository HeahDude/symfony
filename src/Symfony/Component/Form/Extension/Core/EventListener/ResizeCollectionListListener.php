<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Form\Extension\Core\EventListener;

use Symfony\Component\Form\CollectionList\CollectionListInterFace;
use Symfony\Component\Form\CollectionList\Loader\CollectionLoaderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Dynamically resize a collection form element based on the data sent from the client and form config.
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 * @author Jules Pietri <jules@heahprod.com>
 */
class ResizeCollectionListListener extends ResizeFormListener implements EventSubscriberInterface
{
    /**
     * @var string|\Closure The internal value for dynamic "name"
     *                      of the sub from type,  also used as
     *                      the default label
     */
    protected $index;

    /**
     * @var array|\Closure
     */
    protected $options;

    /**
     * @var string|string[]
     */
    protected $type_map;

    /**
     * Optimize collection using parent when true.
     *
     * @var bool
     */
    private $static = false;

    /**
     * @var CollectionListInterface
     */
    private $collectionList;

    public function __construct(CollectionListInterFace $collection, $type_map, $index = null, array $options = array(), $allowAdd = false, $allowDelete = false, $deleteEmpty = false)
    {
        // Keeps high performance and BC performing a "static" resize
        // all method calls will be done by the parent.
        if (is_string($type_map) && null === $index && is_array($options)) {
            $this->static = true;
        } else {
            $this->index = $index;
            $this->type_map = $type_map;
        }

        parent::__construct(null, $options, $allowAdd, $allowDelete, $deleteEmpty);
    }

    public function preSetData(FormEvent $event)
    {
        if ($this->static) {
            return parent::preSetData($event);
        }

        $form = $event->getForm();
        $data = $event->getData();

        if (null === $data) {
            $data = array();
        }

        if (!is_array($data) && !($data instanceof \Traversable && $data instanceof \ArrayAccess)) {
            throw new UnexpectedTypeException($data, 'array or (\Traversable and \ArrayAccess)');
        }

        // First remove all rows
        foreach ($form as $name => $child) {
            $form->remove($name);
        }

        $collection = $this->createCollectionList($data);

        // Then add all rows again in the correct order
        foreach ($collection as $name => $value) {
            $form->add($name, $this->type, array_replace(array(
                'property_path' => '['.$name.']',
            ), $this->options));
        }
    }

    public function preSubmit(FormEvent $event)
    {
        if ($this->static) {
            return parent::preSubmit($event);
        }

        $form = $event->getForm();
        $data = $event->getData();

        if ($data instanceof \Traversable && $data instanceof \ArrayAccess) {
            @trigger_error('Support for objects implementing both \Traversable and \ArrayAccess is deprecated since version 3.1 and will be removed in 4.0. Use an array instead.', E_USER_DEPRECATED);
        }

        if (!is_array($data) && !($data instanceof \Traversable && $data instanceof \ArrayAccess)) {
            $data = array();
        }
        // Remove all empty rows
        if ($this->allowDelete) {
            foreach ($form as $name => $child) {
                if (!isset($data[$name])) {
                    $form->remove($name);
                }
            }
        }

        // Add all additional rows
        if ($this->allowAdd) {
            foreach ($data as $name => $value) {
                if (!$form->has($name)) {
                    $form->add($name, $this->type, array_replace(array(
                        'property_path' => '['.$name.']',
                    ), $this->options));
                }
            }
        }
    }

    public function onSubmit(FormEvent $event)
    {
        if ($this->static) {
            return parent::onSubmit($event);
        }

        $form = $event->getForm();
        $data = $event->getData();

        // At this point, $data is an array or an array-like object that already contains the
        // new entries, which were added by the data mapper. The data mapper ignores existing
        // entries, so we need to manually unset removed entries in the collection.

        if (null === $data) {
            $data = array();
        }

        if (!is_array($data) && !($data instanceof \Traversable && $data instanceof \ArrayAccess)) {
            throw new UnexpectedTypeException($data, 'array or (\Traversable and \ArrayAccess)');
        }

        if ($this->deleteEmpty) {
            $previousData = $event->getForm()->getData();
            foreach ($form as $name => $child) {
                $isNew = !isset($previousData[$name]);

                // $isNew can only be true if allowAdd is true, so we don't
                // need to check allowAdd again
                if ($child->isEmpty() && ($isNew || $this->allowDelete)) {
                    unset($data[$name]);
                    $form->remove($name);
                }
            }
        }

        // The data mapper only adds, but does not remove items, so do this
        // here
        if ($this->allowDelete) {
            $toDelete = array();

            foreach ($data as $name => $child) {
                if (!$form->has($name)) {
                    $toDelete[] = $name;
                }
            }

            foreach ($toDelete as $name) {
                unset($data[$name]);
            }
        }

        $event->setData($data);
    }

    private function createCollectionList($data)
    {
        return $data;
    }
}
