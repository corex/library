<?php

namespace CoRex\Support;

class Str
{
    /**
     * Get length of string.
     *
     * @param string $value
     * @return integer
     */
    public static function length($value)
    {
        return mb_strlen($value);
    }

    /**
     * Convert the given string to lower-case.
     *
     * @param string $value
     * @return string
     */
    public static function lower($value)
    {
        return mb_strtolower($value, 'UTF-8');
    }

    /**
     * Convert the given string to upper-case.
     *
     * @param string $value
     * @return string
     */
    public static function upper($value)
    {
        return mb_strtoupper($value, 'UTF-8');
    }

    /**
     * Returns the portion of string specified by the start and length parameters.
     *
     * @param string $string
     * @param integer $start
     * @param integer|null $length Default null.
     * @return string
     */
    public static function substr($string, $start, $length = null)
    {
        return mb_substr($string, $start, $length, 'UTF-8');
    }

    /**
     * Determine if a given string starts with a given substring.
     *
     * @param  string $haystack
     * @param  string $needle
     * @return boolean
     */
    public static function startsWith($haystack, $needle)
    {
        return static::isPrefixed($haystack, $needle);
    }

    /**
     * Determine if a given string ends with a given substring.
     *
     * @param  string $haystack
     * @param  string $needle
     * @return boolean
     */
    public static function endsWith($haystack, $needle)
    {
        return static::isSuffixed($haystack, $needle);
    }

    /**
     * Make a string's first character uppercase.
     *
     * @param  string $string
     * @return string
     */
    public static function ucfirst($string)
    {
        return static::upper(static::substr($string, 0, 1)) . static::substr($string, 1);
    }

    /**
     * Limit the number of characters in a string.
     *
     * @param string $value
     * @param integer $limit Default 50.
     * @param string $end Default '...'.
     * @return string
     */
    public static function limit($value, $limit = 50, $end = '...')
    {
        if (mb_strwidth($value, 'UTF-8') <= $limit) {
            return $value;
        }
        return rtrim(mb_strimwidth($value, 0, $limit, '', 'UTF-8')) . $end;
    }

    /**
     * Is prefixed.
     *
     * @param string $data
     * @param string $prefix
     * @param string $separator Default ''.
     * @return boolean
     */
    public static function isPrefixed($data, $prefix, $separator = '')
    {
        if ($separator != '') {
            $data = trim($data, $separator);
        }
        return static::substr($data, 0, static::length($prefix)) == $prefix;
    }

    /**
     * Strip prefix.
     *
     * @param string $data
     * @param string $prefix
     * @param string $separator Default ''.
     * @return string
     */
    public static function stripPrefix($data, $prefix, $separator = '')
    {
        if ($data == '') {
            return $data;
        }
        if ($separator != '') {
            $data = ltrim($data, $separator);
        }
        if (static::substr($data, 0, static::length($prefix)) == $prefix) {
            $data = static::substr($data, static::length($prefix));
        }
        if ($separator != '') {
            $data = ltrim($data, $separator);
        }
        return $data;
    }

    /**
     * Force prefix.
     *
     * @param string $data
     * @param string $prefix
     * @param string $separator Default ''.
     * @return string
     */
    public static function forcePrefix($data, $prefix, $separator = '')
    {
        if ($data == '') {
            return $data;
        }
        if ($separator != '') {
            $data = trim($data, $separator);
        }
        if (static::substr($data, 0, static::length($prefix)) != $prefix) {
            if ($separator != '') {
                $prefix .= $separator;
            }
            $data = $prefix . $data;
        }
        return $data;
    }

    /**
     * Is suffixed.
     *
     * @param string $data
     * @param string $suffix
     * @param string $separator Default ''.
     * @return boolean
     */
    public static function isSuffixed($data, $suffix, $separator = '')
    {
        if ($separator != '') {
            $data = trim($data, $separator);
        }
        return static::substr($data, -static::length($suffix)) == $suffix;
    }

    /**
     * Strip suffix.
     *
     * @param string $data
     * @param string $prefix
     * @param string $separator Default ''.
     * @return string
     */
    public static function stripSuffix($data, $prefix, $separator = '')
    {
        if ($data == '') {
            return $data;
        }
        if ($separator != '') {
            $data = rtrim($data, $separator);
        }
        if (static::substr($data, -static::length($prefix)) == $prefix) {
            $data = static::substr($data, 0, -static::length($prefix));
        }
        if ($separator != '') {
            $data = rtrim($data, $separator);
        }
        return $data;
    }

    /**
     * Force suffix.
     *
     * @param string $data
     * @param string $prefix
     * @param string $separator Default ''.
     * @return string
     */
    public static function forceSuffix($data, $prefix, $separator = '')
    {
        if ($data == '') {
            return $data;
        }
        if ($separator != '') {
            $data = trim($data, $separator);
        }
        if (static::substr($data, -static::length($prefix)) != $prefix) {
            if ($separator != '') {
                $data .= $separator;
            }
            $data .= $prefix;
        }
        return $data;
    }

    /**
     * Replace token.
     * Token: '{something}'.
     *
     * @param string $string
     * @param array $data [$key => $value].
     * @return string
     */
    public static function replaceToken($string, array $data)
    {
        if (count($data) > 0) {
            foreach ($data as $key => $value) {
                $string = str_replace('{' . $key . '}', $value, $string);
            }
        }
        return $string;
    }

    /**
     * Removed last entry based on $separator.
     *
     * @param string $string
     * @param string $separator
     * @return string
     */
    public static function removeLast($string, $separator)
    {
        $string = explode($separator, $string);
        $string = Arr::removeLast($string);
        return implode($separator, $string);
    }

    /**
     * Get last part of string based on $separator.
     * @param $string
     * @param $separator
     * @return string
     */
    public static function getLast($string, $separator)
    {
        $string = explode($separator, $string);
        return Arr::getLast($string);
    }
}