<?php

namespace CoRex\Support;

use CoRex\Support\System\File;

class Container
{
    private $data;

    /**
     * Constructor.
     *
     * @param array $data Default null.
     */
    public function __construct(array $data = null)
    {
        $this->clear($data);
    }

    /**
     * Clear.
     *
     * @param array $data Default null.
     */
    public function clear(array $data = null)
    {
        $this->data = array();
        if (is_array($data)) {
            $this->data = $data;
        }
    }

    /**
     * Check if name exist.
     *
     * @param string $path Uses dot notation.
     * @return boolean
     */
    public function exist($path)
    {
        if ((string)$path == '') {
            return true;
        }
        $data = &$this->data;
        $pathSegments = explode('.', $path);
        foreach ($pathSegments as $pathSegment) {
            if (!isset($data[$pathSegment])) {
                return false;
            }
            $data = &$data[$pathSegment];
        }
        return true;
    }

    /**
     * Set value.
     *
     * @param string $path Uses dot notation.
     * @param mixed $value
     */
    public function set($path, $value)
    {
        $key = $this->getKey($path);
        $path = Str::removeLast($path, '.');
        if ($key !== null) {
            $data =& $this->getData($path, true);
            $data[$key] = $value;
        }
    }

    /**
     * Set array (merged by key).
     *
     * @param array $data
     */
    public function setArray(array $data)
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $this->set($key, $value);
            }
        }
    }

    /**
     * Get value.
     *
     * @param string $path Uses dot notation.
     * @param mixed $defaultValue
     * @return mixed
     */
    public function get($path, $defaultValue = null)
    {
        $key = $this->getKey($path);
        $path = Str::removeLast($path, '.');
        if ($key !== null && $this->exist($path)) {
            $data = $this->getData($path);
            if (isset($data[$key])) {
                return $data[$key];
            }
        }
        return $defaultValue;
    }

    /**
     * Delete value.
     *
     * @param string $path Uses dot notation.
     */
    public function delete($path)
    {
        $key = $this->getKey($path);
        $path = Str::removeLast($path, '.');
        if ($key !== null && $this->exist($path)) {
            $data =& $this->getData($path);
            if (isset($data[$key])) {
                unset($data[$key]);
            }
        }
    }

    /**
     * To json.
     *
     * @param boolean $prettyPrint Default true.
     * @return mixed|string
     */
    public function toJson($prettyPrint = true)
    {
        $options = 0;
        if ($prettyPrint) {
            $options += JSON_PRETTY_PRINT;
        }
        return json_encode($this->data, $options);
    }

    /**
     * To array.
     *
     * @return mixed
     */
    public function toArray()
    {
        return $this->data;
    }

    /**
     * Get data from json-file.
     *
     * @param string $filename
     */
    public function getJson($filename)
    {
        $this->data = File::getJson($filename);
    }

    /**
     * Put data to json-file.
     *
     * @param string $filename
     * @param boolean $prettyPrint Default true.
     */
    public function putJson($filename, $prettyPrint = false)
    {
        File::putJson($filename, $this->data, $prettyPrint);
    }

    /**
     * Get data.
     *
     * @param string $path
     * @param boolean $create Default false.
     * @return mixed
     * @throws \Exception
     */
    private function &getData($path, $create = false)
    {
        $data = &$this->data;
        if ((string)$path == '') {
            return $data;
        }
        $pathSegments = explode('.', $path);
        foreach ($pathSegments as $pathSegment) {
            if (!isset($data[$pathSegment]) && !$create) {
                throw new \Exception('Path not found and not in create mode.');
            }
            $data = &$data[$pathSegment];
        }
        return $data;
    }

    /**
     * Get key.
     *
     * @param string $path
     * @return null|string
     */
    private function getKey($path)
    {
        if ((string)$path == '') {
            return null;
        }
        return Str::getLast($path, '.');
    }
}