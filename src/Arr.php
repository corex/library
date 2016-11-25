<?php

namespace CoRex\Support;

class Arr
{
    /**
     * Get value from data.
     *
     * @param array $data
     * @param string $key
     * @param mixed $defaultValue Default null.
     * @return mixed|null
     */
    public static function get($data, $key, $defaultValue = null)
    {
        if (is_array($data) && isset($data[$key])) {
            return $data[$key];
        }
        return $defaultValue;
    }

    /**
     * Get last element of array.
     *
     * @param array $data
     * @param string $key Get key from element if array. Default null.
     * @return mixed
     */
    public static function getLast(array $data, $key = null)
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
     * To associative.
     *
     * @param array $values
     * @param $key
     * @return array
     */
    public static function toAssociative(array $values, $key)
    {
        $result = [];
        if (count($values) > 0) {
            foreach ($values as $value) {
                $result[] = [$key => $value];
            }
        }
        return $result;
    }
}