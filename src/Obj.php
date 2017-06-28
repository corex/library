<?php

namespace CoRex\Support;

use Exception;
use ReflectionClass;
use ReflectionMethod;
use ReflectionProperty;

class Obj
{
    const PROPERTY_PRIVATE = ReflectionProperty::IS_PRIVATE;
    const PROPERTY_PROTECTED = ReflectionProperty::IS_PROTECTED;
    const PROPERTY_PUBLIC = ReflectionProperty::IS_PUBLIC;

    /**
     * Get properties.
     *
     * @param object $object
     * @param string $class Default null which means class from $object.
     * @param integer $propertyType Default null.
     * @return array
     */
    public static function getProperties($object, $class = null, $propertyType = null)
    {
        $reflectionClass = self::getReflectionClass($object, $class);
        $properties = [];
        $reflectionProperties = $reflectionClass->getProperties($propertyType);
        foreach ($reflectionProperties as $property) {
            $property->setAccessible(true);
            $properties[$property->getName()] = $property->getValue($object);
        }
        return $properties;
    }

    /**
     * Get property.
     *
     * @param object $object
     * @param string $property
     * @param mixed $defaultValue Default null.
     * @param string $class Default null which means class from $object.
     * @return mixed
     */
    public static function getProperty($object, $property, $defaultValue = null, $class = null)
    {
        $reflectionClass = self::getReflectionClass($object, $class);
        try {
            $property = $reflectionClass->getProperty($property);
            if ($object === null && !$property->isStatic()) {
                return $defaultValue;
            }
            $property->setAccessible(true);
            return $property->getValue($object);
        } catch (Exception $e) {
            return $defaultValue;
        }
    }

    /**
     * Set properties.
     *
     * @param object $object
     * @param array $propertiesValues Key/value.
     * @param string $class Default null which means class from $object.
     * @return boolean
     */
    public static function setProperties($object, array $propertiesValues, $class = null)
    {
        $reflectionClass = self::getReflectionClass($object, $class);
        if (count($propertiesValues) == 0) {
            return false;
        }
        try {
            foreach ($propertiesValues as $property => $value) {
                $property = $reflectionClass->getProperty($property);
                $property->setAccessible(true);
                $property->setValue($object, $value);
            }
        } catch (Exception $e) {
            return false;
        }
        return true;
    }

    /**
     * Set property.
     *
     * @param object $object
     * @param string $property
     * @param mixed $value
     * @param string $class Default null which means class from $object.
     * @return boolean
     */
    public static function setProperty($object, $property, $value, $class = null)
    {
        $reflectionClass = self::getReflectionClass($object, $class);
        try {
            $property = $reflectionClass->getProperty($property);
            $property->setAccessible(true);
            $property->setValue($object, $value);
        } catch (Exception $e) {
            return false;
        }
        return true;
    }

    /**
     * Call method.
     *
     * @param string $name
     * @param object $object
     * @param array $arguments Default [].
     * @param string $class Default null.
     * @return mixed
     */
    public static function callMethod($name, $object, array $arguments = [], $class = null)
    {
        if ($class === null) {
            $class = get_class($object);
        }
        $method = new ReflectionMethod($class, $name);
        $method->setAccessible(true);
        if (count($arguments) > 0) {
            return $method->invokeArgs($object, $arguments);
        } else {
            return $method->invoke($object);
        }
    }

    /**
     * Get interfaces.
     *
     * @param object $object
     * @return array
     */
    public static function getInterfaces($object)
    {
        return class_implements(get_class($object));
    }

    /**
     * Has interface.
     *
     * @param object $object
     * @param string $interfaceClassName
     * @return boolean
     */
    public static function hasInterface($object, $interfaceClassName)
    {
        return in_array($interfaceClassName, self::getInterfaces($object));
    }

    /**
     * Get reflection class.
     *
     * @param object $object
     * @param string $class Default null which means class from $object.
     * @return \ReflectionClass
     */
    private static function getReflectionClass($object, $class = null)
    {
        if ($class === null) {
            $class = get_class($object);
        }
        return new ReflectionClass($class);
    }
}