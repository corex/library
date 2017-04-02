<?php

namespace CoRex\Support;

use ReflectionClass;
use ReflectionProperty;

class Obj
{
    /**
     * Get private properties from object.
     *
     * @param object $object
     * @return array
     */
    public static function getPrivatePropertiesFromObject($object)
    {
        $className = get_class($object);
        return self::getPrivatePropertiesFromStatic($className, $object);
    }

    /**
     * Get private properties from static class.
     *
     * @param string $className
     * @param object $object Default null which means new $className().
     * @return array
     */
    public static function getPrivatePropertiesFromStatic($className, $object = null)
    {
        $result = [];
        if ($object === null) {
            $object = new $className();
        }
        $reflectionClass = new ReflectionClass($className);
        $properties = $reflectionClass->getProperties(ReflectionProperty::IS_PRIVATE);
        if (count($properties) > 0) {
            foreach ($properties as $property) {
                $property->setAccessible(true);
                $result[$property->getName()] = $property->getValue($object);
            }
        }
        return $result;
    }
}