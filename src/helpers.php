<?php

if (!function_exists('d')) {
    /**
     * Dump arguments.
     */
    function d()
    {
        // Show uses of d/dd.
        if (defined('COREX_SUPPORT_D_USES')) {
            $backtrace = debug_backtrace();
            if (count($backtrace) > 0) {
                $entry = $backtrace[0];
                if (isset($backtrace[1]['function']) && $backtrace[1]['function'] == 'dd') {
                    $entry = $backtrace[1];
                }
                dump($entry['function'] . ' in [' . $entry['file'] . ':' . $entry['line'] . ']');
            }
            return;
        }

        // Dump arguments.
        $arguments = func_get_args();
        foreach ($arguments as $argument) {
            dump($argument);
        }
    }
}

if (!function_exists('dd')) {
    /**
     * Dump arguments and end the script.
     */
    function dd()
    {
        call_user_func_array('d', func_get_args());
        die();
    }
}

if (!function_exists('d_show_uses')) {
    /**
     * Define constant so every d/dd from now on, show where they are used.
     * Might not work in Laravel, since Laravel has its own dd().
     * It depends what is loaded first.
     */
    function d_show_uses()
    {
        define('COREX_SUPPORT_D_USES', true);
    }
}