<?php

namespace CoRex\Support\Code;

class Convention
{
    const PASCALCASE = "pascalCase";
    const CAMELCASE = "camelCase";
    const SNAKECASE = "snakeCase";
    const KEBABCASE = "kebabCase";

    /**
     * Pascal case.
     *
     * @param string $value
     * @return string
     */
    public static function pascalCase($value)
    {
        $value = ucwords(str_replace(['-', '_'], ' ', $value));
        return str_replace(' ', '', $value);
    }

    /**
     * Camel case.
     *
     * @param string $value
     * @return string
     */
    public static function camelCase($value)
    {
        return lcfirst(static::pascalCase($value));
    }

    /**
     * Snake case.
     *
     * @param string $value
     * @param boolean $toLowerCase Default false.
     * @param string $separator Default '_'.
     * @return string
     */
    public static function snakeCase($value, $toLowerCase = false, $separator = '_')
    {
        $replace = strtolower(preg_replace(
            ['/\s+/', '/\s/', '/(?|([a-z\d])([A-Z])|([^\^])([A-Z][a-z]))/', '/[-_]+/'],
            [' ', $separator, '$1' . $separator . '$2', $separator],
            trim($value)
        ));
        return ($toLowerCase) ? strtolower($replace) : $replace;
    }

    /**
     * Kebab case.
     *
     * @param string $value
     * @param boolean $toLowerCase Default true.
     * @return string
     */
    public static function kebabCase($value, $toLowerCase = true)
    {
        return static::snakeCase($value, $toLowerCase, '-');
    }

    /**
     * Convert key case Recursively, using the method defined.
     *
     * @param array $array
     * @param string $method The Convention method to execute. Default pascal().
     * @param string $separator Default '_'.
     * @return array
     */
    public static function convertArrayKeysRecursively(array $array, $method = self::PASCALCASE, $separator = '_')
    {
        $return = [];
        foreach ($array as $key => $value) {
            if (!preg_match('/^\d+$/', $key)) {
                $key = Convention::$method(preg_replace('/\-/', '_', $key), true, $separator);
            }
            if (is_array($value)) {
                $value = self::convertArrayKeysRecursively($value, $method);
            }
            $return[$key] = $value;
        }
        return $return;
    }
}
