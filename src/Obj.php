<?php

namespace CoRex\Support;

use ReflectionClass;
use ReflectionProperty;

class Obj
{
    const PROPERTY_PRIVATE = ReflectionProperty::IS_PRIVATE;
    const PROPERTY_PROTECTED = ReflectionProperty::IS_PROTECTED;
    const PROPERTY_PUBLIC = ReflectionProperty::IS_PUBLIC;

    /**
     * Get private properties from object.
     *
     * @param object $object
     * @param string $className Default '' which means from object.
     * @param integer $propertyType Default 0.
     * @return array
     */
    public static function getPropertiesFromObject($object, $className = '', $propertyType = 0)
    {
        if ($className == '') {
            $className = get_class($object);
        }
        return self::getPropertiesFromStatic($className, $object, $propertyType);
    }

    /**
     * Get private properties from static class.
     *
     * @param string $className
     * @param object $object Default null which means new $className().
     * @param integer $propertyType Default 0.
     * @return array
     */
    public static function getPropertiesFromStatic($className, $object = null, $propertyType = 0)
    {
        $result = [];
        if ($object === null) {
            $object = new $className();
        }
        $reflectionClass = new ReflectionClass($className);
        $properties = $reflectionClass->getProperties($propertyType);
        if (count($properties) > 0) {
            foreach ($properties as $property) {
                $property->setAccessible(true);
                $result[$property->getName()] = $property->getValue($object);
            }
        }
        return $result;
    }
}