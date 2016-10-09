<?php

namespace CoRex\Support\Config;

class Config
{
    private static $app;
    private static $data;

    /**
     * Register app.
     *
     * @param string $path
     * @param string $app App to set path for.
     */
    public static function registerApp($path, $app = null)
    {
        if ($app === null) {
            $app = '*';
        }
        if (substr($path, -1) == '/') {
            $path = substr($path, 0, -1);
        }
        self::$app[$app] = $path;
    }

    /**
     * Load section and parse configuration on constructor.
     *
     * @param string $section
     * @param string $class
     * @param string $app Default null.
     * @return object
     * @throws ConfigException
     */
    public static function getObject($section, $class, $app = null)
    {
        if (!class_exists($class)) {
            throw new ConfigException('Class ' . $class . ' does not exist.');
        }
        $data = self::getSection($section, $app);
        return new $class($data);
    }

    /**
     * Load section, execute and parse on closure.
     *
     * @param string $section
     * @param callable $closure
     * @param string $app Default null.
     * @throws ConfigException
     */
    public static function getClosure($section, $closure, $app = null)
    {
        if (!is_callable($closure)) {
            throw new ConfigException('Closure specified is not callable.');
        }
        $data = self::getSection($section, $app);
        return $closure($data);
    }

    /**
     * Get value.
     *
     * @param string $path Uses dot notation.
     * @param mixed $defaultValue Default null.
     * @param boolean $throwException Default false.
     * @param string $app Default null.
     * @return mixed
     * @throws ConfigException
     */
    public static function get($path, $defaultValue = null, $throwException = false, $app = null)
    {
        // Extract section from path.
        $pathSegments = explode('.', $path);
        $section = $pathSegments[0];
        unset($pathSegments[0]);
        $pathSegments = array_values($pathSegments);

        // Set section data.
        $data = self::getSection($section, $app);
        if ($data === null) {
            if ($throwException) {
                throw new ConfigException('Section ' . $section . ' not found.');
            }
            return $defaultValue;
        }

        // Extract on path.
        foreach ($pathSegments as $pathSegment) {
            if (isset($data[$pathSegment])) {
                $data = &$data[$pathSegment];
            } else {
                if ($throwException) {
                    throw new ConfigException('Path ' . $path . ' not found.');
                }
                $data = $defaultValue;
            }
        }

        return $data;
    }

    /**
     * Get section. Return null if not found.
     *
     * @param string $section
     * @param string $app Default null.
     * @return mixed
     */
    public static function getSection($section, $app = null)
    {
        self::initialize();
        $sectionData = null;
        if ($app === null) {
            $app = '*';
        }
        if (!isset(self::$data[$app][$section])) {
            self::load($section, $app);
        }
        if (isset(self::$data[$app][$section])) {
            $sectionData = self::$data[$app][$section];
        }
        return $sectionData;
    }

    /**
     * Get apps (registered).
     *
     * @return array
     */
    public static function getApps()
    {
        return self::$app;
    }

    /**
     * Get data.
     *
     * @return array
     */
    public static function getData()
    {
        return self::$data;
    }

    /**
     * Load section.
     *
     * @param string $section
     * @param string $app Default null.
     * @return boolean
     */
    private static function load($section, $app = null)
    {
        self::initialize();
        if ($app === null) {
            $app = '*';
        }
        if (!isset(self::$app[$app])) {
            return false;
        }
        $filename = self::$app[$app] . '/' . $section . '.php';
        if (!file_exists($filename)) {
            return false;
        }
        self::$data[$app][$section] = require($filename);
        return true;
    }

    /**
     * Initialize.
     */
    private static function initialize()
    {
        if (!is_array(self::$data)) {
            self::$data = [];
        }
        if (!is_array(self::$app)) {
            self::$app = [];
        }
    }
}