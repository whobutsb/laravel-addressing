<?php

namespace Galahad\LaravelAddressing\Collection;

use IteratorAggregate;
use Traversable;

/**
 * Class Collection
 *
 * @package Galahad\LaravelAddressing
 * @author Junior Grossi <juniorgro@gmail.com>
 */
class Collection implements IteratorAggregate
{
    /**
     * @var array
     */
    private $items = [];

    /**
     * Retrieve an external iterator
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return Traversable An instance of an object implementing <b>Iterator</b> or
     * <b>Traversable</b>
     * @since 5.0.0
     */
    public function getIterator()
    {
        return new Iterator($this->items);
    }

    /**
     * Insert a new element in the collection
     *
     * @param mixed $value
     */
    public function insert($value)
    {
        if (is_array($value)) {
            foreach ($value as $val) {
                $this->items[] = $val;
            }
            return;
        }
        $this->items[] = $value;
    }

    /**
     * Get the items count
     *
     * @return int
     */
    public function count()
    {
        return count($this->items);
    }

    /**
     * Get an element by its position
     *
     * @param int $key
     * @return mixed
     */
    public function getByKey($key)
    {
        if (isset($this->items[$key])) {
            return $this->items[$key];
        }
    }
}