<?php

namespace CoRex\Support;

abstract class Errors
{
    /**
     * Get all.
     *
     * @return array
     */
    public static function messages()
    {
        $reflectionClass = new \ReflectionClass(get_called_class());
        $constants = $reflectionClass->getConstants();
        $messages = [];
        foreach ($constants as $constant => $properties) {
            if (empty($properties[0]) || empty($properties[1])) {
                continue;
            }
            $status = $properties[0];
            $message = $properties[1];
            $messages[$constant] = [
                'code' => $constant,
                'status' => $status,
                'text' => $message
            ];
        }
        return $messages;
    }

    /**
     * Get.
     *
     * @param string $constant
     * @param array $params Default [].
     * @return string
     */
    public static function message($constant, array $params = [])
    {
        $message = self::findMessage($constant);
        if ($message === null) {
            return null;
        }
        $text = $message['text'];
        if (count($params) > 0) {
            foreach ($params as $key => $value) {
                $text = str_replace('{' . $key . '}', $value, $text);
            }
        }
        return $text;
    }

    /**
     * Get code.
     *
     * @param string $constant
     * @return null
     */
    public static function code($constant)
    {
        $message = self::findMessage($constant);
        if ($message === null) {
            return null;
        }
        return $message['code'];
    }

    public static function status($constant)
    {
        $message = self::findMessage($constant);
        if ($message === null) {
            return null;
        }
        return $message['status'];
    }

    private static function findMessage($constant)
    {
        if (empty($constant[0]) || empty($constant[1])) {
            return null;
        }
        $constantMessage = $constant[1];
        $messages = self::messages();
        foreach ($messages as $message) {
            if ($message['text'] == $constantMessage) {
                return $message;
            }
        }
        return null;
    }
}