<?php

namespace CoRex\Support;

class Collection implements \Iterator, \Countable
{
    private $items;

    /**
     * Data constructor.
     *
     * @param array $items Default null which means [].
     */
    public function __construct(array $items = null)
    {
        if ($items === null) {
            $items = [];
        }
        $this->items = $items;
    }

    /**
     * To json.
     *
     * @param boolean $prettyPrint Default false.
     * @return string
     */
    public function toJson($prettyPrint = false)
    {
        $options = 0;
        if ($prettyPrint) {
            $options += JSON_PRETTY_PRINT;
        }
        return json_encode($this->items, $options);
    }

    /**
     * Get count.
     *
     * @return integer
     */
    public function count()
    {
        if ($this->items !== null) {
            return count($this->items);
        }
        return 0;
    }

    /**
     * Return the current item.
     *
     * @return mixed
     */
    public function current()
    {
        if ($this->items === null) {
            return null;
        }
        return current($this->items);
    }

    /**
     * Move forward to next item.
     *
     * @return $this
     */
    public function next()
    {
        if ($this->items === null) {
            return null;
        }
        next($this->items);
        return $this;
    }

    /**
     * Return the key of the current item.
     *
     * @return mixed
     */
    public function key()
    {
        if ($this->items === null) {
            return null;
        }
        return key($this->items);
    }

    /**
     * Checks if current position is valid.
     *
     * @return boolean
     */
    public function valid()
    {
        if ($this->items === null) {
            return false;
        }
        $key = key($this->items);
        return ($key !== null && $key !== false);
    }

    /**
     * Rewind the Iterator to the first item.
     *
     * @return $this
     */
    public function rewind()
    {
        if ($this->items === null) {
            return null;
        }
        reset($this->items);
        return $this;
    }

    /**
     * Get first item in collection.
     *
     * @return mixed
     */
    public function first()
    {
        $this->rewind();
        return $this->current();
    }

    /**
     * Get last element in collection.
     *
     * @return mixed|null
     */
    public function last()
    {
        if (count($this->items) > 0) {
            return end($this->items);
        }
        return null;
    }

    /**
     * Get all items.
     *
     * @return array|mixed|null
     */
    public function all()
    {
        return $this->items;
    }

    /**
     * Run a callable/closure on each element.
     *
     * @param callable $callable Parameters: ($item, $key). Prefix $item with & to modify $item.
     * @return $this
     * @throws \Exception
     */
    public function each(callable $callable)
    {
        foreach ($this->items as $key => $item) {
            if ($callable($item, $key) === false) {
                break;
            }
            $this->items[$key] = $item;
        }
        return $this;
    }

    /**
     * Pluck.
     *
     * @param string $path
     * @param mixed $defaultValue Default null.
     * @return $this
     */
    public function pluck($path, $defaultValue = null)
    {
        $result = [];
        if (count($this->items) == 0) {
            return null;
        }
        foreach ($this->items as $key => $item) {
            if (is_object($item)) {
                $value = isset($item->{$path}) ? $item->{$path} : null;
            } else {
                $value = Arr::get($item, $path, $defaultValue);
            }
            $result[] = $value;
        }
        return new static($result);
    }

    /**
     * Sum.
     *
     * @param string $path
     * @param integer $defaultValue Default 0.
     * @return float|integer
     */
    public function sum($path, $defaultValue = 0)
    {
        return array_sum($this->pluck($path, $defaultValue)->all());
    }

    /**
     * Average.
     *
     * @param string $path
     * @param integer $defaultValue Default 0.
     * @return float|integer
     */
    public function average($path, $defaultValue = 0)
    {
        if (count($this->items) == 0) {
            return $defaultValue;
        }
        $sum = $this->sum($path, $defaultValue);
        if ($sum == 0) {
            return 0;
        }
        return $sum / count($this->items);
    }

    /**
     * Max.
     *
     * @param string $path
     * @param integer $defaultValue Default 0.
     * @return integer
     */
    public function max($path, $defaultValue = 0)
    {
        if (count($this->items) == 0) {
            return $defaultValue;
        }
        $items = $this->pluck($path, $defaultValue);
        $max = 0;
        foreach ($items as $item) {
            if ($item > $max) {
                $max = $item;
            }
        }
        return $max;
    }

    /**
     * Min.
     *
     * @param string $path
     * @param integer $defaultValue Default 0.
     * @return integer
     */
    public function min($path, $defaultValue = 0)
    {
        if (count($this->items) == 0) {
            return $defaultValue;
        }
        $items = $this->pluck($path, $defaultValue);
        $min = PHP_INT_MAX;
        foreach ($items as $item) {
            if ($item < $min) {
                $min = $item;
            }
        }
        return $min;
    }

    /**
     * Reverse.
     *
     * @return Collection
     */
    public function reverse()
    {
        return new static(array_reverse($this->items, true));
    }

    /**
     * Keys.
     *
     * @return array
     */
    public function keys()
    {
        if (is_array($this->items)) {
            return array_keys($this->items);
        }
        return [];
    }

    /**
     * Values.
     *
     * @return array
     */
    public function values()
    {
        return array_values($this->items);
    }

    /**
     * Get.
     *
     * @param string $key
     * @param mixed $defaultValue Default null.
     * @return mixed
     */
    public function get($key, $defaultValue = null)
    {
        if ($this->has($key)) {
            return $this->items[$key];
        }
        return $defaultValue;
    }

    /**
     * Has.
     *
     * @param string $key
     * @return boolean
     */
    public function has($key)
    {
        return in_array($key, $this->keys());
    }

    /**
     * Add.
     *
     * @param mixed $value
     * @return $this
     */
    public function add($value)
    {
        $this->items[] = $value;
        return $this;
    }

    /**
     * Set.
     *
     * @param string $key
     * @param mixed $value
     * @return $this
     */
    public function set($key, $value)
    {
        $this->items[$key] = $value;
        return $this;
    }

    /**
     * Delete.
     *
     * @param string $key
     * @return $this
     */
    public function delete($key)
    {
        if (isset($this->items[$key])) {
            unset($this->items[$key]);
        }
        return $this;
    }
}