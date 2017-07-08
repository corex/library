<?php

namespace CoRex\Support;

class Str
{
    const LIMIT_SUFFIX = '...';

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
     * Left.
     *
     * @param string $string
     * @param integer $count
     * @return string
     */
    public static function left($string, $count)
    {
        return self::substr($string, 0, $count);
    }

    /**
     * Right.
     *
     * @param string $string
     * @param integer $count
     * @return string
     */
    public static function right($string, $count)
    {
        return self::substr($string, -$count);
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
     * Make a string's first character lowercase.
     *
     * @param string $string
     * @return string
     */
    public static function lcfirst($string)
    {
        return static::lower(static::substr($string, 0, 1)) . static::substr($string, 1);
    }

    /**
     * Limit the number of characters in a string.
     *
     * @param string $value
     * @param integer $limit Default 50.
     * @param string $end Default '...'.
     * @return string
     */
    public static function limit($value, $limit = 50, $end = self::LIMIT_SUFFIX)
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
     * @param array $data Must be specified as [$key => $value].
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
     * Removed first entry based on $separator.
     *
     * @param string $string
     * @param string $separator
     * @return string
     */
    public static function removeFirst($string, $separator)
    {
        $string = explode($separator, $string);
        $string = Arr::removeFirst($string);
        return implode($separator, $string);
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
     * Get first part of string based on $separator.
     *
     * @param string $string
     * @param string $separator
     * @return string
     */
    public static function first($string, $separator)
    {
        $string = explode($separator, $string);
        return Arr::first($string);
    }

    /**
     * Get last part of string based on $separator.
     *
     * @param string $string
     * @param string $separator
     * @return string
     */
    public static function last($string, $separator)
    {
        $string = explode($separator, $string);
        return Arr::last($string);
    }

    /**
     * Get part.
     *
     * @param string $string
     * @param string $separator
     * @param integer $index
     * @param string $defaultValue Default ''.
     * @return string
     */
    public static function part($string, $separator, $index, $defaultValue = '')
    {
        if ($string != '') {
            $string = explode($separator, $string);
            if (isset($string[$index])) {
                return $string[$index];
            }
        }
        return $defaultValue;
    }

    /**
     * Get CSV fields. Removes ' and ".
     *
     * @param string $line
     * @param string $delimiter Default ','.
     * @return array
     */
    public static function csvFields($line, $delimiter = ',')
    {
        if (trim($line) == '') {
            return [];
        }
        $fields = [];
        $parts = str_getcsv($line, $delimiter);
        if ($parts !== null && count($parts) > 0) {
            foreach ($parts as $part) {
                $part = trim($part);
                if (substr($part, 0, 1) == '"' || substr($part, 0, 1) == '\'') {
                    $part = substr($part, 1);
                }
                if (substr($part, -1) == '"' || substr($part, -1) == '\'') {
                    $part = substr($part, 0, -1);
                }
                if ($part != '') {
                    $fields[] = $part;
                }
            }
        }
        return $fields;
    }

    /**
     * Slug.
     * Standard separator characters '-', '_', ' ', '.'.
     *
     * @param string $string
     * @param string $separator Default '.'.
     * @return string
     */
    public static function slug($string, $separator = '.')
    {
        // Make sure standard characters has been replaced to separator.
        $slug = str_replace(['-', '_', ' ', '.'], $separator, mb_strtolower($string));

        // Remove all "funny" characters.
        $slug = preg_replace('/[^a-z0-9' . preg_quote($separator) . ']/', '', $slug);

        return $slug;
    }

    /**
     * Split into key/value array.
     * Note: 'slice of' elements if keys and values are not the same length.
     *
     * @param string $string
     * @param string $separator
     * @param array $keys
     * @return array
     */
    public static function splitIntoKeyValue($string, $separator, array $keys)
    {
        $parts = explode($separator, $string);
        $result = [];
        if (count($keys) > 0) {

            // Make sure arrays has equal number of items.
            if (count($parts) > count($keys)) {
                $parts = array_slice($parts, 0, count($keys));
            }
            if (count($keys) > count($parts)) {
                $keys = array_slice($keys, 0, count($parts));
            }

            $result = array_combine($keys, $parts);
        }
        return $result;
    }

    /**
     * Create a unique string.
     *
     * @param string $prefix Default ''.
     * @param string $suffix Default ''.
     * @return string
     */
    public static function unique($prefix = '', $suffix = '')
    {
        $unique = md5(mt_rand());
        if ($prefix != '') {
            $unique = $prefix . $unique;
        }
        if ($suffix != '') {
            $unique .= $suffix;
        }
        return $unique;
    }

    /**
     * Explode string into items.
     *
     * @param string $separator If "\n", "\r", will be removed before explode.
     * @param string $content
     * @param callable $itemFunction
     * @return array
     */
    public static function explode($separator, $content, callable $itemFunction = null)
    {
        if ($separator == "\n") {
            $content = str_replace("\r", '', $content);
        }
        $items = explode($separator, $content);
        if (is_callable($itemFunction)) {
            foreach ($items as $index => $item) {
                $items[$index] = $itemFunction($item);
            }
        }
        return $items;
    }

    /**
     * Implode items into string.
     *
     * @param string $separator
     * @param array $items
     * @param callable $itemFunction
     * @return string
     */
    public static function implode($separator, array $items, callable $itemFunction = null)
    {
        if (is_callable($itemFunction)) {
            foreach ($items as $index => $item) {
                $items[$index] = $itemFunction($item);
            }
        }
        return implode($separator, $items);
    }

    /**
     * Pad left.
     *
     * @param string $string
     * @param integer $length
     * @param string $filler Default ' '.
     * @return string
     */
    public static function padLeft($string, $length, $filler = ' ')
    {
        while (self::length($string) <= ($length - self::length($filler))) {
            $string = $filler . $string;
        }
        return $string;
    }

    /**
     * Pad right.
     *
     * @param string $string
     * @param integer $length
     * @param string $filler Default ' '.
     * @return string
     */
    public static function padRight($string, $length, $filler = ' ')
    {
        while (self::length($string) <= ($length - self::length($filler))) {
            $string = $string . $filler;
        }
        return $string;
    }

    /**
     * Wrap.
     *
     * @param string $text
     * @param integer $length
     * @param string $separator Default "\n".
     * @return string
     */
    public static function wrap($text, $length, $separator = "\n")
    {
        if ($text == '' || Str::length($text) == $length) {
            return $text;
        }
        $endedWithLinebreak = substr($text, -1) == "\n";
        $text = str_replace("\r", "", $text);
        $text = str_replace("\n", "", $text);
        $text = explode(" ", $text);

        $result = [];
        $lineNo = 0;
        foreach ($text as $word) {
            if (!isset($result[$lineNo])) {
                $result[$lineNo] = "";
            }
            $lastLineNo = count($result) - 1;

            if ((Str::length($result[$lastLineNo]) + Str::length($word)) >= $length) {
                $lineNo++;
                if (!isset($result[$lineNo])) {
                    $result[$lineNo] = "";
                }
                $lastLineNo = count($result) - 1;
            }

            $result[$lastLineNo] .= $result[$lastLineNo] != "" ? " " : "";
            $result[$lastLineNo] .= $word;
        }

        $text = implode($separator, $result);
        if ($endedWithLinebreak) {
            $text .= "\n";
        }
        return $text;
    }
}