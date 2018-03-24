<?php

namespace CoRex\Support;

class Bag
{
    private $properties;

    /**
     * Constructor.
     *
     * @param array $properties
     */
    public function __construct(array $properties = [])
    {
        $this->clear($properties);
    }

    /**
     * Clear.
     *
     * @param array $properties
     */
    public function clear(array $properties = [])
    {
        $this->properties = [];
        if ($properties === null) {
            $properties = [];
        }
        foreach ($properties as $key => $value) {
            $this->set($key, $value);
        }
    }

    /**
     * Check if key exist.
     *
     * @param string $key Uses dot notation.
     * @return boolean
     */
    public function has($key)
    {
        $key = $this->prepareKey($key);
        return Arr::has($this->properties, $key);
    }

    /**
     * Set key/value.
     *
     * @param string $key Uses dot notation.
     * @param mixed $value
     * @param boolean $create Default false.
     */
    public function set($key, $value, $create = false)
    {
        $key = $this->prepareKey($key);
        Arr::set($this->properties, $key, $value, $create);
    }

    /**
     * Set array (merged by key).
     *
     * @param array $data
     * @param boolean $create Default false.
     */
    public function setArray(array $data, $create = false)
    {
        foreach ($data as $key => $value) {
            $this->set($key, $value, $create);
        }
    }

    /**
     * Get value.
     *
     * @param string $key Uses dot notation.
     * @param mixed $defaultValue
     * @return mixed
     */
    public function get($key, $defaultValue = null)
    {
        $key = $this->prepareKey($key);
        return Arr::get($this->properties, $key, $defaultValue);
    }

    /**
     * Remove$key.
     *
     * @param string $key Uses dot notation.
     */
    public function remove($key)
    {
        $key = $this->prepareKey($key);
        $this->properties = Arr::remove($this->properties, $key);
    }

    /**
     * Keys.
     *
     * @return array
     */
    public function keys()
    {
        return array_keys($this->all());
    }

    /**
     * All.
     *
     * @return array
     */
    public function all()
    {
        return (array)$this->properties;
    }

    /**
     * Prepare key.
     *
     * @param string $key
     * @return string
     */
    protected function prepareKey($key)
    {
        return $key;
    }
}