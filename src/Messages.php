<?php

namespace CoRex\Support;

abstract class Messages
{
    /**
     * Get all.
     *
     * @return array
     */
    public static function all()
    {
        $reflectionClass = new \ReflectionClass(get_called_class());
        return $reflectionClass->getConstants();
    }

    /**
     * Get.
     *
     * @param string $constant
     * @param array $params Default [].
     * @return string
     */
    public static function get($constant, array $params = [])
    {
        $constants = array_flip(self::all());
        if (!isset($constants[$constant])) {
            return null;
        }
        $message = $constant;
        if (count($params) > 0) {
            foreach ($params as $key => $value) {
                $message = str_replace('{' . $key . '}', $value, $message);
            }
        }
        return $message;
    }

    /**
     * Get code.
     *
     * @param string $constant
     * @return null
     */
    public static function getCode($constant)
    {
        $constants = array_flip(self::all());
        if (isset($constants[$constant])) {
            return $constants[$constant];
        }
        return null;
    }
}