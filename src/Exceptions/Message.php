<?php

namespace CoRex\Support\Exceptions;

class Message
{
    const SYSTEM_ERROR = [1, 'System error occurred'];
    const SYSTEM_CLASS_NOT_FOUND = [2, 'Class {class} not found'];
    const SYSTEM_NOT_VALID_MESSAGE = [3, 'Not valid message constant'];

    /**
     * All.
     *
     * @param string $className
     * @return array
     */
    public static function all($className)
    {
        if (!class_exists($className)) {
            self::throwException(self::SYSTEM_CLASS_NOT_FOUND, [
                'class' => $className
            ]);
        }
        $messages = [];
        $reflectionClass = new \ReflectionClass($className);
        $constants = $reflectionClass->getConstants();
        foreach ($constants as $constant => $message) {
            if (!isset($message[0]) && !isset($message[1])) {
                self::throwException(self::SYSTEM_NOT_VALID_MESSAGE);
            }
            $code = $message[0];
            $message = $message[1];
            $messages[$code] = $message;
        }
        return $messages;
    }

    /**
     * Get.
     *
     * @param array $constant
     * @param array $params Default [].
     * @return string
     */
    public static function get(array $constant, array $params = [])
    {
        if (!is_array($constant) || count($constant) != 2) {
            self::throwException(self::SYSTEM_NOT_VALID_MESSAGE);
        }
        $message = $constant[1];
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
     * @param array $constant
     * @return mixed
     */
    public static function getCode(array $constant)
    {
        if (!is_array($constant) || count($constant) != 2) {
            self::throwException(self::SYSTEM_NOT_VALID_MESSAGE);
        }
        return $constant[0];
    }

    /**
     * Throw exception.
     *
     * @param array $constant
     * @param array $params Default [].
     * @throws \Exception
     */
    private static function throwException(array $constant, array $params = [])
    {
        throw new \Exception(
            self::get($constant, $params),
            self::getCode($constant)
        );
    }
}