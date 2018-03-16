<?php

namespace CoRex\Support;

class Arr
{
    /**
     * Get value from data.
     *
     * @param array $data
     * @param string $path Uses dot notation.
     * @param mixed $defaultValue Default null.
     * @return mixed|null
     * @throws \Exception
     */
    public static function get(array $data, $path, $defaultValue = null)
    {
        $data = self::dataByPath($data, $path);
        if ($data === null) {
            $data = $defaultValue;
        }
        return $data;
    }

    /**
     * Set value in data.
     *
     * @param array $array
     * @param string $path Uses dot notation.
     * @param mixed $value
     * @param boolean $create Default false.
     * @throws \Exception
     */
    public static function set(array &$array, $path, $value, $create = false)
    {
        // Extract key/path.
        $pathKey = Str::last($path, '.');
        $path = Str::removeLast($path, '.');

        // Extract data.
        $pathArray = null;
        if ($path != '' && $path !== null) {
            $array = &self::dataByPath($array, $path, $create);
        }
        if ($array !== null || $create) {
            $array[$pathKey] = $value;
        }
    }

    /**
     * Get first element of array.
     *
     * @param array $data
     * @param string $key Get key from element if array. Default null.
     * @return mixed
     */
    public static function first(array $data, $key = null)
    {
        if (count($data) == 0) {
            return null;
        }
        reset($data);
        $element = current($data);
        if ($key !== null && is_array($element) && isset($element[$key])) {
            return $element[$key];
        }
        return $element;
    }

    /**
     * Get last element of array.
     *
     * @param array $data
     * @param string $key Get key from element if array. Default null.
     * @return mixed
     */
    public static function last(array $data, $key = null)
    {
        if (count($data) == 0) {
            return null;
        }
        $element = end($data);
        if ($key !== null && is_array($element) && isset($element[$key])) {
            return $element[$key];
        }
        return $element;
    }

    /**
     * Remote first element of array.
     *
     * @param array $data
     * @return array
     */
    public static function removeFirst(array $data)
    {
        if (count($data) > 0) {
            array_shift($data);
        }
        return $data;
    }

    /**
     * Remove last element of array.
     *
     * @param array $data
     * @return array
     */
    public static function removeLast(array $data)
    {
        if (count($data) > 0) {
            unset($data[count($data) - 1]);
        }
        return $data;
    }

    /**
     * Is list (0..n).
     *
     * @param array $data
     * @return boolean
     */
    public static function isList(array $data)
    {
        $isList = true;
        if (count($data) > 0) {
            for ($c1 = 0; $c1 < count($data); $c1++) {
                if (!isset($data[$c1])) {
                    $isList = false;
                }
            }
        }
        return $isList;
    }

    /**
     * Is string in list.
     *
     * @param array $list
     * @param string $key Default null.
     * @return boolean
     */
    public static function isStringInList(array $list, $key = null)
    {
        $stringInList = false;
        if (count($list) == 0) {
            return $stringInList;
        }
        foreach ($list as $item) {
            if ($key !== null && isset($item[$key])) {
                $value = $item[$key];
            } else {
                $value = $item;
            }
            if (!is_numeric($value)) {
                $stringInList = true;
            }
        }
        return $stringInList;
    }

    /**
     * Index of.
     * Note: Supports array with objects.
     *
     * @param array $array
     * @param string $value
     * @param string $key Default null which means the item itself (not associative array).
     * @return integer -1 if not found.
     */
    public static function indexOf(array $array, $value, $key = null)
    {
        if (!is_array($array)) {
            return -1;
        }
        foreach ($array as $index => $item) {
            if ($key !== null) {
                if (is_object($item)) {
                    $checkValue = isset($item->{$key}) ? $item->{$key} : null;
                } else {
                    $checkValue = isset($item[$key]) ? $item[$key] : null;
                }
            } else {
                $checkValue = $item;
            }
            if ($checkValue !== null && $checkValue === $value) {
                return $index;
            }
        }
        return -1;
    }

    /**
     * Check if all keys exist in array.
     *
     * @param array $data
     * @param array $keys
     * @return boolean
     */
    public static function keysExist(array $data, array $keys)
    {
        if (count($keys) > 0) {
            foreach ($keys as $key) {
                if (!array_key_exists($key, $data)) {
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * Keys.
     *
     * @param array $array
     * @return array
     */
    public static function keys(array $array)
    {
        return array_keys($array);
    }

    /**
     * Values (0..n).
     *
     * @param array $array
     * @return array
     */
    public static function values(array $array)
    {
        return array_values($array);
    }

    /**
     * Is associative.
     *
     * @param array $array
     * @return boolean
     */
    public static function isAssociative(array $array)
    {
        $keys = array_keys($array);
        return array_keys($keys) !== $keys;
    }

    /**
     * Pluck.
     *
     * @param array $array
     * @param string $path Uses dot notation.
     * @param mixed $defaultValue Default null.
     * @return array
     * @throws \Exception
     */
    public static function pluck(array $array, $path, $defaultValue = null)
    {
        $result = [];
        if (!is_array($array)) {
            return $result;
        }

        // Extract key/path.
        $pathKey = Str::last($path, '.');
        $path = Str::removeLast($path, '.');

        // Extract data.
        if ($path != '' && $path !== null) {
            $array = self::dataByPath($array, $path);
        }
        foreach ($array as $item) {
            $value = $defaultValue;
            if (is_object($item) && isset($item->{$pathKey})) {
                $value = $item->{$pathKey};
            } elseif (is_array($item) && isset($item[$pathKey])) {
                $value = $item[$pathKey];
            }
            $result[] = $value;
        }

        return $result;
    }

    /**
     * Get line match.
     *
     * @param array $lines
     * @param string $prefix
     * @param string $suffix
     * @param boolean $doTrim
     * @param boolean $removePrefixSuffix Default false.
     * @return array
     */
    public static function lineMatch(array $lines, $prefix, $suffix, $doTrim, $removePrefixSuffix = false)
    {
        $result = [];
        foreach ($lines as $line) {
            $isHit = true;
            if ($prefix != '' && $prefix !== null && Str::startsWith(trim($line), $prefix)) {
                if ($removePrefixSuffix) {
                    $line = substr(trim($line), strlen($prefix));
                }
            } else {
                $isHit = false;
            }
            if ($suffix != '' && $suffix !== null && Str::endsWith(trim($line), $suffix)) {
                if ($removePrefixSuffix) {
                    $line = substr(trim($line), 0, -strlen($suffix));
                }
            } else {
                $isHit = false;
            }
            if ($isHit) {
                if ($doTrim) {
                    $line = trim($line);
                }
                $result[] = $line;
            }
        }
        return $result;
    }

    /**
     * To array.
     *
     * @param string|array $stringOrArray
     * @param string $separator Default '.'.
     * @return array
     */
    public static function toArray($stringOrArray, $separator = '.')
    {
        if (is_string($stringOrArray)) {
            if (trim($stringOrArray) != '') {
                $stringOrArray = explode($separator, $stringOrArray);
            }
        }
        if (!is_array($stringOrArray)) {
            return [];
        }
        return $stringOrArray;
    }

    /**
     * Get data by path.
     *
     * @param array $data
     * @param string $path
     * @param boolean $create Default false.
     * @param mixed $defaultValue Default null.
     * @return mixed
     * @throws \Exception
     */
    private static function &dataByPath(array &$data, $path, $create = false, $defaultValue = null)
    {
        if ((string)$path == '') {
            return $data;
        }
        $pathSegments = explode('.', $path);
        foreach ($pathSegments as $pathSegment) {
            if (!isset($data[$pathSegment]) && !$create) {
                return $defaultValue;
            }
            $data = &$data[$pathSegment];
        }
        return $data;
    }
}