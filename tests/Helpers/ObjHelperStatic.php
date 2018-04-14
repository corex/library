<?php

class ObjHelperStatic
{
    private static $property1 = 'property 1';
    private static $property2 = 'property 2';
    private static $property3 = 'property 3';
    private static $property4 = 'property 4';

    /**
     * Private method.
     *
     * @param string $arguments
     * @return string
     */
    private static function privateMethod($arguments = '')
    {
        return '(' . __FUNCTION__ . ')' . $arguments;
    }
}