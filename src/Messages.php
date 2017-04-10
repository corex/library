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
     * Message.
     *
     * @param array $constant
     * @return mixed|null
     */
    public static function message(array $constant)
    {
        return self::findMessage($constant);
    }

    /**
     * Code.
     *
     * @param array $constant
     * @return null
     */
    public static function code(array $constant)
    {
        $message = self::findMessage($constant);
        if ($message === null) {
            return null;
        }
        return $message['code'];
    }

    /**
     * Message.
     *
     * @param array $constant
     * @param array $params Default [].
     * @return string
     */
    public static function text(array $constant, array $params = [])
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
     * Status.
     *
     * @param array $constant
     * @return null
     */
    public static function status(array $constant)
    {
        $message = self::findMessage($constant);
        if ($message === null) {
            return null;
        }
        return $message['status'];
    }

    /**
     * Find message.
     *
     * @param array $constant
     * @return mixed|null
     */
    private static function findMessage(array $constant)
    {
        if (empty($constant[0]) || empty($constant[1])) {
            return null;
        }
        $constantMessage = $constant[1];
        $messages = self::all();
        foreach ($messages as $message) {
            if ($message['text'] == $constantMessage) {
                return $message;
            }
        }
        return null;
    }
}