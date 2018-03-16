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
     * @param string $classOverride Default null which means class from $object.
     * @param integer $propertyType Default null.
     * @return array
     * @throws Exception
     * @throws \ReflectionException
     */
    public static function getProperties($object, $classOverride = null, $propertyType = null)
    {
        $reflectionClass = self::getReflectionClass($object, $classOverride);
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
     * @param string $property
     * @param object $object
     * @param mixed $defaultValue Default null.
     * @param string $classOverride Default null which means class from $object.
     * @return mixed
     * @throws Exception
     * @throws \ReflectionException
     */
    public static function getProperty($property, $object, $defaultValue = null, $classOverride = null)
    {
        $reflectionClass = self::getReflectionClass($object, $classOverride);
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
     * @param string $classOverride Default null which means class from $object.
     * @return boolean
     * @throws Exception
     * @throws \ReflectionException
     */
    public static function setProperties($object, array $propertiesValues, $classOverride = null)
    {
        $reflectionClass = self::getReflectionClass($object, $classOverride);
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
     * @param string $property
     * @param object $object
     * @param mixed $value
     * @param string $classOverride Default null which means class from $object.
     * @return boolean
     * @throws Exception
     * @throws \ReflectionException
     */
    public static function setProperty($property, $object, $value, $classOverride = null)
    {
        $reflectionClass = self::getReflectionClass($object, $classOverride);
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
     * @param string $classOverride Default null.
     * @return mixed
     * @throws \ReflectionException
     */
    public static function callMethod($name, $object, array $arguments = [], $classOverride = null)
    {
        if ($classOverride === null) {
            $classOverride = get_class($object);
        }
        $method = new ReflectionMethod($classOverride, $name);
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
     * @param object|string $objectOrClass
     * @return array
     */
    public static function getInterfaces($objectOrClass)
    {
        if (is_object($objectOrClass)) {
            $objectOrClass = get_class($objectOrClass);
        }
        return class_implements($objectOrClass);
    }

    /**
     * Has interface.
     *
     * @param object|string $objectOrClass
     * @param string $interfaceClassName
     * @return boolean
     */
    public static function hasInterface($objectOrClass, $interfaceClassName)
    {
        return in_array($interfaceClassName, self::getInterfaces($objectOrClass));
    }

    /**
     * Get extends.
     *
     * @param object|string $objectOrClass
     * @return array
     */
    public static function getExtends($objectOrClass)
    {
        if (is_object($objectOrClass)) {
            $objectOrClass = get_class($objectOrClass);
        }
        return array_values(class_parents($objectOrClass));
    }

    /**
     * Has extends.
     *
     * @param object|string $objectOrClass
     * @param string $class
     * @return boolean
     */
    public static function hasExtends($objectOrClass, $class)
    {
        return in_array($class, self::getExtends($objectOrClass));
    }

    /**
     * Get reflection class.
     *
     * @param object|string $objectOrClass
     * @param string $classOverride Default null which means class from $object.
     * @return \ReflectionClass
     * @throws \ReflectionException
     */
    private static function getReflectionClass($objectOrClass, $classOverride = null)
    {
        $class = $classOverride;
        if ($class === null) {
            if (is_object($objectOrClass)) {
                $class = get_class($objectOrClass);
            } else {
                $class = $objectOrClass;
            }
        }
        return new ReflectionClass($class);
    }
}