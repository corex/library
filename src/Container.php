<?php

namespace CoRex\Support;

/**
 * Class Container
 * Note: this container has nothing to do with ioc.
 *
 * @package CoRex\Support
 */
class Container
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
        if ($properties === null) {
            $properties = [];
        }
        $this->properties = $properties;
    }

    /**
     * Check if key exist.
     *
     * @param string $key Uses dot notation.
     * @return boolean
     * @throws \Exception
     */
    public function has($key)
    {
        return Arr::has($this->properties, $key);
    }

    /**
     * Set key/value.
     *
     * @param string $key Uses dot notation.
     * @param mixed $value
     * @param boolean $create Default false.
     * @throws \Exception
     */
    public function set($key, $value, $create = false)
    {
        Arr::set($this->properties, $key, $value, $create);
    }

    /**
     * Set array (merged by key).
     *
     * @param array $data
     * @param boolean $create Default false.
     * @throws \Exception
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
     * @throws \Exception
     */
    public function get($key, $defaultValue = null)
    {
        return Arr::get($this->properties, $key, $defaultValue);
    }

    /**
     * Remove$key.
     *
     * @param string $key Uses dot notation.
     * @throws \Exception
     */
    public function remove($key)
    {
        $this->properties = Arr::remove($this->properties, $key);
    }

    /**
     * To json.
     *
     * @param boolean $prettyPrint Default true.
     * @return string
     */
    public function toJson($prettyPrint = true)
    {
        $options = 0;
        if ($prettyPrint) {
            $options += JSON_PRETTY_PRINT;
        }
        return json_encode($this->properties, $options);
    }

    /**
     * To array.
     *
     * @return array
     */
    public function toArray()
    {
        return (array)$this->properties;
    }
}