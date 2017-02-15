<?php

namespace CoRex\Support\System;

use CoRex\Support\Str;

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
     * @param mixed $defaultValue Default ''.
     * @return string
     */
    public static function load($filename, $defaultValue = '')
    {
        if (!self::exist($filename)) {
            return $defaultValue;
        }
        return file_get_contents($filename);
    }

    /**
     * Load as lines.
     *
     * @param string $filename
     * @param array $defaultValue Default [].
     * @return array
     */
    public static function loadLines($filename, array $defaultValue = [])
    {
        $content = self::load($filename);
        $content = str_replace("\r", '', $content);
        if (trim($content) != '') {
            return explode("\n", $content);
        }
        return $defaultValue;
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
     * Save lines.
     *
     * @param string $filename
     * @param array $lines
     * @param string $separator Default "\n".
     */
    public static function saveLines($filename, array $lines, $separator = "\n")
    {
        self::save($filename, implode($separator, $lines));
    }

    /**
     * Get stub.
     *
     * @param string $filename
     * @param array $tokens Default []. Format ['token' => 'value']. Replaces {token} with value.
     * @param mixed $defaultContent Default ''.
     * @return string
     */
    public static function getStub($filename, array $tokens = [], $defaultContent = '')
    {
        return self::getTemplate($filename, $tokens, $defaultContent, '.stub');
    }

    /**
     * Get template.
     *
     * @param string $filename
     * @param array $tokens Default []. Format ['token' => 'value']. Replaces {token} with value.
     * @param mixed $defaultContent Default ''.
     * @param string $extension Default 'tpl'.
     * @return string
     */
    public static function getTemplate($filename, array $tokens = [], $defaultContent = '', $extension = 'tpl')
    {
        if (!Str::endsWith($filename, '.' . $extension)) {
            $filename .= '.' . $extension;
        }
        if (!self::exist($filename)) {
            return $defaultContent;
        }
        $template = self::load($filename, $defaultContent);
        if ($template != '' && count($tokens) > 0) {
            foreach ($tokens as $token => $value) {
                $template = str_replace('{' . $token . '}', $value, $template);
            }
        }
        return $template;
    }

    /**
     * Load json.
     *
     * @param string $filename
     * @param array $defaultValue Default [].
     * @return array
     */
    public static function loadJson($filename, array $defaultValue = [])
    {
        if (!Str::endsWith($filename, '.json')) {
            $filename .= '.json';
        }
        $data = self::load($filename);
        if ($data == '') {
            return $defaultValue;
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
        if (!Str::endsWith($filename, '.json')) {
            $filename .= '.json';
        }
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