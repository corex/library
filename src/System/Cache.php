<?php

namespace CoRex\Support\System;

class Cache
{
    private static $marker = '||';
    private static $path;
    private static $seconds = 0;

    /**
     * Generate key based on input key and parameters. Used in more sophisticated keys.
     *
     * @param string $key
     * @param array $params Default [].
     * @return string
     */
    public static function key($key, array $params = [])
    {
        $key = md5(serialize($key));
        if (count($params) > 0) {
            $key .= '-' . md5(serialize($params));
        }
        return $key;
    }

    /**
     * Set/get path for stores.
     *
     * @param string $path Default null which means not set.
     * @param boolean $force Default false.
     * @return string If null, then not set.
     * @throws \Exception
     */
    public static function path($path = null, $force = false)
    {
        if ($path !== null || $force) {
            if ($path !== null) {
                if (!is_writeable($path)) {
                    throw new \Exception('Path is not writable.');
                }
                if (substr($path, -1) == '/') {
                    $path = substr($path, 0, -1);
                }
            }
            self::$path = $path;
        }
        return self::$path;
    }

    /**
     * Set/get lifetime.
     *
     * @param string $lifetime Add 'm' for minutes, 'h' for hours. If not specified, seconds are assumed.
     * @param string $store Default 'global'.
     * @return integer Lifetime in seconds.
     */
    public static function lifetime($lifetime = null, $store = 'global')
    {
        if ($lifetime !== null) {
            $seconds = strtolower($lifetime);

            // Convert to minutes if hour.
            if (substr($seconds, -1) == 'h') {
                $seconds = (intval($seconds) * 60) . 'm';
            }

            // Convert to seconds if minutes.
            if (substr($seconds, -1) == 'm') {
                $seconds = (intval($seconds) * 60) . 's';
            }

            if (!is_array(self::$seconds)) {
                self::$seconds = [];
            }
            self::$seconds[$store] = intval($seconds);
        }
        if (isset(self::$seconds[$store])) {
            return self::$seconds[$store];
        }
        return 0;
    }

    /**
     * Get expiration from key.
     *
     * @param string $key
     * @param string $store Default 'global'.
     * @return integer If not exist, 0 is returned.
     */
    public static function expiration($key, $store = 'global')
    {
        if (!self::has($key)) {
            return null;
        }

        // Get content.
        $fileKey = self::key($key);
        $content = null;
        if (file_exists(self::$path . '/' . $store . '/' . $fileKey)) {
            $content = file_get_contents(self::$path . '/' . $store . '/' . $fileKey);
        }
        if ($content === null) {
            return null;
        }

        // Extract expiration.
        $markerPos = strpos($content, self::$marker);
        $expiration = 0;
        if ($markerPos !== false) {
            $expiration = substr($content, 0, $markerPos);
        }

        return $expiration;
    }

    /**
     * Get cache.
     *
     * @param string $key
     * @param mixed $defaultValue Default null.
     * @param string $store Default 'global'.
     * @return mixed|null
     */
    public static function get($key, $defaultValue = null, $store = 'global')
    {
        if (!self::has($key)) {
            return $defaultValue;
        }

        // Get content.
        $fileKey = self::key($key);
        $content = null;
        if (file_exists(self::$path . '/' . $store . '/' . $fileKey)) {
            $content = file_get_contents(self::$path . '/' . $store . '/' . $fileKey);
        }
        if ($content === null) {
            return null;
        }

        // Extract expiration.
        $markerPos = strpos($content, self::$marker);
        $expiration = null;
        if ($markerPos !== false) {
            $expiration = substr($content, 0, $markerPos);
        }

        // Extract content.
        if ($expiration < time()) {
            self::forget($key);
        } else {
            return unserialize(substr($content, $markerPos + strlen(self::$marker)));
        }

        return $defaultValue;
    }

    /**
     * Put cache.
     *
     * @param string $key
     * @param mixed $value
     * @param string $store Default 'global'.
     */
    public static function put($key, $value, $store = 'global')
    {
        self::initialize($store);
        $expiration = time() + intval(self::$seconds);
        $value = $expiration . self::$marker . serialize($value);
        $fileKey = self::key($key);
        @file_put_contents(self::$path . '/' . $store . '/' . $fileKey, $value);
    }

    /**
     * Has key.
     *
     * @param string $key
     * @param string $store Default 'global'.
     * @return boolean
     */
    public static function has($key, $store = 'global')
    {
        $fileKey = self::key($key);
        return file_exists(self::$path . '/' . $store . '/' . $fileKey);
    }

    /**
     * Forget key.
     *
     * @param string $key
     * @param string $store Default 'global'.
     */
    public static function forget($key, $store = 'global')
    {
        $fileKey = self::key($key);
        if (self::has($key)) {
            @unlink(self::$path . '/' . $store . '/' . $fileKey);
        }
    }

    /**
     * Flush store.
     *
     * @param string $store Default 'global'.
     */
    public static function flush($store = 'global')
    {
        if (!is_dir(self::$path . '/' . $store)) {
            return;
        }
        $filenames = scandir(self::$path . '/' . $store);
        if (count($filenames) > 0) {
            foreach ($filenames as $filename) {
                if (substr($filename, 0, 1) == '.') {
                    continue;
                }
                @unlink(self::$path . '/' . $store . '/' . $filename);
            }
        }
    }

    /**
     * Initialize.
     *
     * @param string $store
     * @throws \Exception
     */
    private static function initialize($store)
    {
        if (self::$path === null) {
            throw new \Exception('Path not set.');
        }
        if (self::lifetime(null, $store) == 0) {
            throw new \Exception('Lifetime not set.');
        }
        Directory::make(self::$path . '/' . $store);
    }
}