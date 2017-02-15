<?php

namespace CoRex\Support\System;

class Path
{
    /**
     * Get path to root of site.
     *
     * @param array $segments Default [].
     * @return string
     */
    public static function getRoot(array $segments = [])
    {
        $path = __DIR__;
        for ($c1 = 0; $c1 < 5; $c1++) {
            $path = dirname($path);
        }
        $path = str_replace('\\', '/', $path);
        if (count($segments) > 0) {
            $path .= '/' . implode('/', $segments);
        }
        return $path;
    }

    /**
     * Get path to current package.
     *
     * @param array $segments Default [].
     * @return string
     */
    public static function getPackageCurrent(array $segments = [])
    {
        return self::getPackage(null, null, $segments);
    }

    /**
     * Get path to package.
     * Note: if both $vendor and $package is null, current package is returned.
     *
     * @param string $vendor Default null which means current.
     * @param string $package Default null which means current.
     * @param array $segments Default [].
     * @return string
     */
    public static function getPackage($vendor = null, $package = null, array $segments = [])
    {
        if ($vendor === null & $package === null) {
            $vendor = static::getVendorName();
            $package = static::getPackageName();
        }
        $path = static::getRoot(['vendor']);
        if ($vendor !== null) {
            $path .= '/' . $vendor;
            if ($package !== null) {
                $path .= '/' . $package;
            }
        }
        if (count($segments) > 0) {
            $path .= '/' . implode('/', $segments);
        }
        return $path;
    }

    /**
     * Get vendor name.
     *
     * @return string
     */
    public static function getVendorName()
    {
        $path = static::getPackagePath();
        return basename(dirname($path));
    }

    /**
     * Get package name.
     *
     * @return string
     */
    public static function getPackageName()
    {
        $path = static::getPackagePath();
        return basename($path);
    }

    /**
     * Get package path.
     *
     * @return string
     */
    protected static function getPackagePath()
    {
        return dirname(dirname(__DIR__));
    }
}
