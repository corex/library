<?php

namespace CoRex\Support\System;

class File
{
    /**
     * Check if file exist.
     *
     * @param string $filename
     * @return boolean
     */
    public static function exist($filename)
    {
        return file_exists($filename);
    }

    /**
     * Load.
     *
     * @param string $filename
     * @param mixed $defaultValue Default null.
     * @return string
     */
    public static function load($filename, $defaultValue = null)
    {
        if (!self::exist($filename)) {
            return $defaultValue;
        }
        return file_get_contents($filename);
    }

    /**
     * Save.
     *
     * @param string $filename
     * @param mixed $content
     */
    public static function save($filename, $content)
    {
        file_put_contents($filename, $content);
    }

    /**
     * Get stub.
     *
     * @param string $filename
     * @param array $tokens Default []. Format ['token' => 'value'].
     * @param mixed $defaultContent Default ''.
     * @return string
     */
    public static function getStub($filename, $tokens = [], $defaultContent = '')
    {
        if (substr($filename, -5) != '.stub') {
            $filename .= '.stub';
        }
        if (!self::exist($filename)) {
            return $defaultContent;
        }
        $stub = self::load($filename, $defaultContent);
        if ($stub !== null && count($tokens) > 0) {
            foreach ($tokens as $token => $value) {
                $stub = str_replace('{' . $token . '}', $value, $stub);
            }
        }
        return $stub;
    }

    /**
     * Load json.
     *
     * @param string $filename
     * @param array $defaultValue Default [].
     * @return array
     */
    public static function loadJson($filename, $defaultValue = [])
    {
        $data = self::load($filename);
        if ($data === null) {
            $data = $defaultValue;
        }
        $data = json_decode($data, true);
        if (is_null($data) || $data === false) {
            $data = [];
        }
        return $data;
    }

    /**
     * Save json.
     *
     * @param string $filename
     * @param array $data
     * @param boolean $prettyPrint Default true.
     */
    public static function saveJson($filename, array $data, $prettyPrint = true)
    {
        $options = 0;
        if ($prettyPrint) {
            $options += JSON_PRETTY_PRINT;
        }
        $data = json_encode($data, $options);
        self::save($filename, $data);
    }

    /**
     * Get temp filename.
     *
     * @param string $prefix
     * @param string $extension
     * @param string $path Default '' which means sys_get_temp_dir().
     * @return string
     */
    public static function getTempFilename($prefix = '', $extension = '', $path = '')
    {
        if ($path == '') {
            $path = sys_get_temp_dir();
        }
        $tempFilename = tempnam($path, $prefix);
        if ($extension != '') {
            $tempFilename .= '.' . $extension;
        }
        return $tempFilename;
    }
}