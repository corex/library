<?php

namespace CoRex\Support\System;

class Token
{
    public static $namespace = '-token-';

    /**
     * Clear all tokens.
     */
    public static function clear()
    {
        Session::clear(self::$namespace);
    }

    /**
     * Create token.
     *
     * @param string $name
     * @param integer $lifetime Default 300 seconds.
     * @return string
     */
    public static function create($name, $lifetime = 300)
    {
        $token = sha1(microtime());
        $tokenData = [
            'token' => $token,
            'time' => time(),
            'lifetime' => $lifetime
        ];
        Session::set($name, $tokenData, self::$namespace);
        return $token;
    }

    /**
     * Get token.
     *
     * @param string $name
     * @return string|null
     */
    public static function get($name)
    {
        $data = Session::get($name, null, self::$namespace);
        if (is_array($data) && array_key_exists('token', $data)) {
            return $data['token'];
        }
        return null;
    }

    /**
     * Has.
     *
     * @param string $name
     * @return boolean
     */
    public static function has($name)
    {
        return Session::has($name, self::$namespace);
    }

    /**
     * Is token valid.
     *
     * @param string $name
     * @param string $token
     * @return boolean
     */
    public static function isValid($name, $token)
    {
        $savedToken = Session::get($name, null, self::$namespace);
        if (isset($savedToken['token'])) {
            if (time() >= $savedToken['time'] + $savedToken['lifetime']) {
                $savedToken['token'] = '';
            }
            return $token == $savedToken['token'];
        }
        return false;
    }

    /**
     * Delete.
     *
     * @param string $name
     */
    public static function delete($name)
    {
        Session::delete($name, self::$namespace);
    }
}