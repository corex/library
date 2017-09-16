<?php

namespace CoRex\Support;

use CoRex\Support\System\Directory;
use CoRex\Support\System\File;
use CoRex\Support\System\Path;

class Config
{
    private static $app;
    private static $data;

    /**
     * Clear.
     */
    public static function clear()
    {
        self::$app = [];
        self::$data = [];
    }

    /**
     * Register app.
     *
     * @param string $path
     * @param string $app App to set path for.
     */
    public static function registerApp($path, $app = null)
    {
        self::initialize();
        if ($app === null) {
            $app = '*';
        }
        if (substr($path, -1) == '/') {
            $path = substr($path, 0, -1);
        }
        self::$app[$app] = $path;
        self::loadFiles($app);
    }

    /**
     * Load section and parse configuration on constructor.
     *
     * @param string $section
     * @param string $class
     * @param string $app Default null.
     * @return object
     * @throws \Exception
     */
    public static function getObject($section, $class, $app = null)
    {
        self::initialize();
        if (!class_exists($class)) {
            throw new \Exception('Class ' . $class . ' does not exist.');
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
     * @throws \Exception
     */
    public static function getClosure($section, callable $closure, $app = null)
    {
        self::initialize();
        if (!is_callable($closure)) {
            throw new \Exception('Closure specified is not callable.');
        }
        $data = self::getSection($section, $app);
        return $closure($data);
    }

    /**
     * Get keys.
     *
     * @param string $path Uses dot notation.
     * @param string $app Default null.
     * @return array
     */
    public static function getKeys($path, $app = null)
    {
        $result = self::get($path, [], $app);
        if (!is_array($result)) {
            $result = [];
        }
        return array_keys($result);
    }

    /**
     * Get value.
     *
     * @param string $path Uses dot notation.
     * @param mixed $defaultValue Default null.
     * @param string $app Default null.
     * @return mixed
     * @throws \Exception
     */
    public static function get($path, $defaultValue = null, $app = null)
    {
        self::initialize();
        return self::getAppData($app, $path, '.', $defaultValue);
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
        return self::get($section, null, $app);
    }

    /**
     * Get apps (registered).
     *
     * @return array
     */
    public static function getApps()
    {
        self::initialize();
        return self::$app;
    }

    /**
     * Get data.
     *
     * @param boolean $loadFiles Default true.
     * @return array
     */
    public static function getData($loadFiles = true)
    {
        self::initialize(false, $loadFiles);
        return self::$data;
    }

    /**
     * Set.
     *
     * @param string $path Uses dot notation.
     * @param mixed $value
     * @param string $app Default null.
     */
    public static function set($path, $value, $app = null)
    {
        self::initialize(false, false);
        if (strpos($path, '.') > 0) {
            $key = Str::last($path, '.');
            $path = Str::removeLast($path, '.');
        } else {
            $key = $path;
            $path = '';
        }
        $data =& self::getAppData($app, $path);
        if (!is_array($data)) {
            $data = [];
        }
        if ($key != '') {
            $data[$key] = $value;
        } else {
            $data = $value;
        }
    }

    /**
     * Set config file.
     *
     * @param string $filename
     * @param mixed $defaultConfig Default null.
     * @param string $app Default null.
     */
    public static function setConfigFile($filename, $defaultConfig = null, $app = null)
    {
        $dataPath = Str::stripSuffix(File::basename($filename), 'php', '.');
        if (File::extension($filename) != 'php') {
            $filename .= '.php';
        }
        if (File::exist($filename)) {
            $config = self::loadFile($filename);
        } else {
            $config = $defaultConfig;
        }
        self::set($dataPath, $config, $app);
    }

    /**
     * Initialize.
     *
     * @param boolean $clear Default false.
     * @param boolean $loadFiles Default true.
     */
    private static function initialize($clear = false, $loadFiles = true)
    {
        if (!is_array(self::$data) || $clear) {
            self::$data = [];
        }
        if (!is_array(self::$app) || $clear) {
            self::$app = [];
        }
        if (!isset(self::$app['*']) || $clear) {
            self::$app['*'] = Path::root(['config']);
            if ($loadFiles) {
                self::loadFiles('*');
            }
        }
    }

    /**
     * Load files.
     *
     * @param string $app
     */
    private static function loadFiles($app)
    {
        if (!isset(self::$app['*'])) {
            return;
        }
        $path = self::$app[$app];
        self::$data[$app] = [];
        if (!Directory::exist($path)) {
            return;
        }

        // Get entries recursive.
        $entries = Directory::entries($path, '*', [Directory::TYPE_FILE], true);
        if (count($entries) == 0) {
            return;
        }

        // Sort after level.
        usort($entries, function ($entry1, $entry2) {
            if ($entry1['level'] == $entry2['level']) {
                return 0;
            }
            return ($entry1['level'] < $entry2['level']) ? -1 : 1;
        });

        // Load files.
        foreach ($entries as $entry) {
            if (!Str::endsWith($entry['name'], '.php')) {
                continue;
            }
            $pathRelative = trim(substr($entry['path'], strlen($entry['pathRoot'])), '/');
            $pathRelative .= '/' . Str::removeLast($entry['name'], '.');
            $pathRelative = trim($pathRelative, '/');

            $appData =& self::getAppData($app, $pathRelative, '/');
            if (!is_array($appData)) {
                $appData = [];
            }

            $filename = $entry['path'] . '/' . $entry['name'];
            if (!file_exists($filename)) {
                continue;
            }
            $config = self::loadFile($filename);
            if (is_array($appData) && is_array($config)) {
                foreach ($config as $key => $value) {
                    $appData[$key] = $value;
                }
            } else {
                $appData = $config;
            }
        }
    }

    /**
     * Load file.
     *
     * @param string $filename
     * @param mixed $defaultValue Default null.
     * @return mixed
     */
    private static function loadFile($filename, $defaultValue = null)
    {
        if (!File::exist($filename)) {
            return $defaultValue;
        }
        return require($filename);
    }

    /**
     * Get app data.
     *
     * @param string $app
     * @param string $path
     * @param string $separator Default '.'.
     * @param string $defaultValue Default null.
     * @return null
     */
    private static function &getAppData($app, $path, $separator = '.', $defaultValue = null)
    {
        if ($app === null) {
            $app = '*';
        }
        if (!isset(self::$data[$app])) {
            self::$data[$app] = [];
        }
        $data = &self::$data[$app];
        if ((string)$path == '') {
            return $data;
        }
        $null = null;
        $pathSegments = explode($separator, $path);
        foreach ($pathSegments as $pathSegment) {
            if (!isset($data[$pathSegment]) && $defaultValue !== null) {
                return $defaultValue;
            }
            $data = &$data[$pathSegment];
        }
        return $data;
    }
}