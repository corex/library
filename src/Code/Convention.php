<?php
namespace CoRex\Support\Code;

class Convention
{
    const STUDLY = "studly";
    const PASCAL = "pascal";
    const CAMEL = "camel";
    const SNAKE = "snake";
    const KEBAB = "kebab";

    /**
     * Studly case.
     *
     * @param string $value
     * @return string
     */
    public static function studly($value)
    {
        $value = ucwords(str_replace(['-', '_'], ' ', $value));
        return str_replace(' ', '', $value);
    }

    /**
     * Pascal case.
     *
     * @param string $value
     * @return string
     */
    public static function pascal($value)
    {
        return static::studly($value);
    }

    /**
     * Camel case.
     *
     * @param string $value
     * @return string
     */
    public static function camel($value)
    {
        return lcfirst(static::studly($value));
    }

    /**
     * Snake case.
     *
     * @param string $value
     * @param boolean $toLowerCase Default false.
     * @param string $separator Default '_'.
     * @return string
     */
    public static function snake($value, $toLowerCase = false, $separator = '_')
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
    public static function kebab($value, $toLowerCase = true)
    {
        return static::snake($value, $toLowerCase, '-');
    }

    /**
     * Convert key case Recursively, using the method defined.
     *
     * @param array $array
     * @param string $method The Convention method to execute. Default studly().
     * @param string $separator Default '_'.
     * @return array
     */
    public static function convertArrayKeysRecursively(array $array, $method = self::STUDLY, $separator = '_')
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
