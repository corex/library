<?php

use CoRex\Support\Obj;
use PHPUnit\Framework\TestCase;

class ObjTest extends TestCase
{
    private $checkProperties = [
        'property1' => 'property 1',
        'property2' => 'property 2',
        'property3' => 'property 3',
        'property4' => 'property 4'
    ];

    /**
     * Test get private properties from object.
     *
     * @throws ReflectionException
     */
    public function testGetPrivatePropertiesFromObject()
    {
        $objHelperObject = new ObjHelperObject();
        $properties = Obj::getProperties($objHelperObject, null, Obj::PROPERTY_PRIVATE);
        $this->assertEquals($this->checkProperties, $properties);
    }

    /**
     * Test get private properties from static.
     *
     * @throws ReflectionException
     */
    public function testGetPrivatePropertiesFromStatic()
    {
        $properties = Obj::getProperties(null, ObjHelperStatic::class, Obj::PROPERTY_PRIVATE);
        $this->assertEquals($this->checkProperties, $properties);
    }

    /**
     * Test get interfaces with from object.
     */
    public function testGetInterfacesWithFromObject()
    {
        $objHelperWithInterface = new ObjHelperWithInterface();
        $interfaces = Obj::getInterfaces($objHelperWithInterface);
        $this->assertArrayHasKey(ObjHelperInterface::class, $interfaces);
    }

    /**
     * Test get interfaces with from class.
     */
    public function testGetInterfacesWithFromClass()
    {
        $interfaces = Obj::getInterfaces(ObjHelperWithInterface::class);
        $this->assertArrayHasKey(ObjHelperInterface::class, $interfaces);
    }

    /**
     * Test get interfaces without from object.
     */
    public function testGetInterfacesWithoutFromObject()
    {
        $objHelperWithoutInterface = new ObjHelperWithoutInterface();
        $interfaces = Obj::getInterfaces($objHelperWithoutInterface);
        $this->assertArrayNotHasKey(ObjHelperInterface::class, $interfaces);
    }

    /**
     * Test get interfaces without from class.
     */
    public function testGetInterfacesWithoutFromClass()
    {
        $interfaces = Obj::getInterfaces(ObjHelperWithoutInterface::class);
        $this->assertArrayNotHasKey(ObjHelperInterface::class, $interfaces);
    }

    /**
     * Test has interface with from object.
     */
    public function testHasInterfaceWithFromObject()
    {
        $objHelperWithInterface = new ObjHelperWithInterface();
        $this->assertTrue(Obj::hasInterface($objHelperWithInterface, ObjHelperInterface::class));
    }

    /**
     * Test has interface with from class.
     */
    public function testHasInterfaceWithFromClass()
    {
        $this->assertTrue(Obj::hasInterface(ObjHelperWithInterface::class, ObjHelperInterface::class));
    }

    /**
     * Test has interface without from object.
     */
    public function testHasInterfaceWithoutFromObject()
    {
        $objHelperWithoutInterface = new ObjHelperWithoutInterface();
        $this->assertFalse(Obj::hasInterface($objHelperWithoutInterface, ObjHelperInterface::class));
    }

    /**
     * Test has interface without from class.
     */
    public function testHasInterfaceWithoutFromClass()
    {
        $this->assertFalse(Obj::hasInterface(ObjHelperWithoutInterface::class, ObjHelperInterface::class));
    }

    /**
     * Test getExtends with from object.
     */
    public function testGetExtendsWithFromObject()
    {
        $objHelperObjectExtended = new ObjHelperObjectExtended();
        $extends = Obj::getExtends($objHelperObjectExtended);
        $this->assertTrue(in_array(ObjHelperObject::class, $extends));
    }

    /**
     * Test getExtends with from class.
     */
    public function testGetExtendsWithFromClass()
    {
        $extends = Obj::getExtends(ObjHelperObjectExtended::class);
        $this->assertTrue(in_array(ObjHelperObject::class, $extends));
    }

    /**
     * Test getExtends without from object.
     */
    public function testGetExtendsWithoutFromObject()
    {
        $objHelperObject = new ObjHelperObject();
        $extends = Obj::getExtends($objHelperObject);
        $this->assertEquals([], $extends);
    }

    /**
     * Test getExtends without from class.
     */
    public function testGetExtendsWithoutFromClass()
    {
        $extends = Obj::getExtends(ObjHelperObject::class);
        $this->assertEquals([], $extends);
    }

    /**
     * Test hasExtends with from object.
     */
    public function testHasExtendsWithFromObject()
    {
        $objHelperObjectExtended = new ObjHelperObjectExtended();
        $this->assertTrue(Obj::hasExtends($objHelperObjectExtended, ObjHelperObject::class));
    }

    /**
     * Test hasExtends with from class.
     */
    public function testHasExtendsWithFromClass()
    {
        $this->assertTrue(Obj::hasExtends(ObjHelperObjectExtended::class, ObjHelperObject::class));
    }

    /**
     * Test hasExtends without from object.
     */
    public function testHasExtendsWithoutFromObject()
    {
        $objHelperObject = new ObjHelperObject();
        $this->assertFalse(Obj::hasExtends($objHelperObject, ObjHelperObject::class));
    }

    /**
     * Test hasExtends without from class.
     */
    public function testHasExtendsWithoutFromClass()
    {
        $this->assertFalse(Obj::hasExtends(ObjHelperObject::class, ObjHelperObject::class));
    }

    /**
     * Test hasMethod private from object.
     */
    public function testHasMethodPrivateFromObject()
    {
        $objHelperObject = new ObjHelperObject();
        $this->assertTrue(Obj::hasMethod('privateMethod', $objHelperObject));
    }

    /**
     * Test hasMethod protected from object.
     */
    public function testHasMethodProtectedFromObject()
    {
        $objHelperObject = new ObjHelperObject();
        $this->assertTrue(Obj::hasMethod('protectedMethod', $objHelperObject));
    }

    /**
     * Test hasMethod public from object.
     */
    public function testHasMethodPublicFromObject()
    {
        $objHelperObject = new ObjHelperObject();
        $this->assertTrue(Obj::hasMethod('publicMethod', $objHelperObject));
    }

    /**
     * Test hasMethod private from class.
     */
    public function testHasMethodPrivateFromClass()
    {
        $this->assertTrue(Obj::hasMethod('privateMethod', ObjHelperObject::class));
    }

    /**
     * Test hasMethod protected from class.
     */
    public function testHasMethodProtectedFromClass()
    {
        $this->assertTrue(Obj::hasMethod('protectedMethod', ObjHelperObject::class));
    }

    /**
     * Test hasMethod public from class.
     */
    public function testHasMethodPublicFromClass()
    {
        $this->assertTrue(Obj::hasMethod('publicMethod', ObjHelperObject::class));
    }

    /**
     * Test hasMethod private from extended class.
     */
    public function testHasMethodPrivateFromExtendedClass()
    {
        $this->assertTrue(Obj::hasMethod('privateMethod', ObjHelperObjectExtended::class));
    }

    /**
     * Test hasMethod protected from extended class.
     */
    public function testHasMethodProtectedFromExtendedClass()
    {
        $this->assertTrue(Obj::hasMethod('protectedMethod', ObjHelperObjectExtended::class));
    }

    /**
     * Test hasMethod public from extended class.
     */
    public function testHasMethodPublicFromExtendedClass()
    {
        $this->assertTrue(Obj::hasMethod('publicMethod', ObjHelperObjectExtended::class));
    }

    /**
     * Test hasMethod no class.
     */
    public function testHasMethodNoClass()
    {
        $this->assertFalse(Obj::hasMethod('unknown', 'unknown'));
    }

    /**
     * Test set property.
     *
     * @throws ReflectionException
     */
    public function testSetPropertyFound()
    {
        $check1 = md5(microtime(true)) . '1';
        $check2 = md5(microtime(true)) . '2';
        $check3 = md5(microtime(true)) . '3';
        $check4 = md5(microtime(true)) . '4';

        $objHelperObject = new ObjHelperObject();
        $this->assertTrue(Obj::setProperty('property1', $objHelperObject, $check1));
        $this->assertTrue(Obj::setProperty('property2', $objHelperObject, $check2));
        $this->assertTrue(Obj::setProperty('property3', $objHelperObject, $check3));
        $this->assertTrue(Obj::setProperty('property4', $objHelperObject, $check4));

        $properties = Obj::getProperties($objHelperObject, null, Obj::PROPERTY_PRIVATE);
        $this->assertEquals($check1, $properties['property1']);
        $this->assertEquals($check2, $properties['property2']);
        $this->assertEquals($check3, $properties['property3']);
        $this->assertEquals($check4, $properties['property4']);
    }

    /**
     * Test set property not found.
     *
     * @throws ReflectionException
     */
    public function testSetPropertyNotFound()
    {
        $check = md5(microtime(true));
        $objHelperObject = new ObjHelperObject();
        $property = Obj::setProperty('unknown', $objHelperObject, $check);
        $this->assertFalse($property);
    }

    /**
     * Test get property not found.
     *
     * @throws ReflectionException
     */
    public function testGetPropertyNotFound()
    {
        $check = md5(microtime(true));
        $objHelperObject = new ObjHelperObject();
        $property = Obj::getProperty('unknown', $objHelperObject, $check);
        $this->assertEquals($check, $property);
    }

    /**
     * Test get property found.
     *
     * @throws ReflectionException
     */
    public function testGetPropertyFound()
    {
        $check1 = md5(microtime(true));
        $check2 = md5(microtime(true));
        $objHelperObject = new ObjHelperObject();
        Obj::setProperty('property1', $objHelperObject, $check1);
        Obj::setProperty('property2', $objHelperObject, $check2);
        $this->assertEquals($check1, Obj::getProperty('property1', $objHelperObject));
        $this->assertEquals($check2, Obj::getProperty('property2', $objHelperObject));
    }

    /**
     * Test get property found static.
     */
    public function testGetPropertyFoundStatic()
    {
        $check1 = md5(mt_rand(1, 100000));
        $check2 = md5(mt_rand(1, 100000));
        $check3 = md5(mt_rand(1, 100000));
        $check4 = md5(mt_rand(1, 100000));
        Obj::setProperty('property1', null, $check1, ObjHelperStatic::class);
        Obj::setProperty('property2', null, $check2, ObjHelperStatic::class);
        Obj::setProperty('property3', null, $check3, ObjHelperStatic::class);
        Obj::setProperty('property4', null, $check4, ObjHelperStatic::class);
        $value1 = Obj::getProperty('property1', null, null, ObjHelperStatic::class);
        $value2 = Obj::getProperty('property2', null, null, ObjHelperStatic::class);
        $value3 = Obj::getProperty('property3', null, null, ObjHelperStatic::class);
        $value4 = Obj::getProperty('property4', null, null, ObjHelperStatic::class);
        $this->assertEquals($check1, $value1);
        $this->assertEquals($check2, $value2);
        $this->assertEquals($check3, $value3);
        $this->assertEquals($check4, $value4);
    }

    /**
     * Test get property found static null.
     */
    public function testGetPropertyFoundStaticNull()
    {
        $objHelperObject = new ObjHelperObject();
        Obj::setProperty('property1', $objHelperObject, null);
        $value1 = Obj::getProperty('property1', null, null, ObjHelperObject::class);
        $this->assertNull($value1);
    }

    /**
     * Test set properties.
     */
    public function testSetPropertiesFound()
    {
        $propertiesValues = [
            'property1' => md5(microtime(true)) . '1',
            'property2' => md5(microtime(true)) . '2',
            'property3' => md5(microtime(true)) . '3',
            'property4' => md5(microtime(true)) . '4'
        ];
        $objHelperObject = new ObjHelperObject();
        Obj::setProperties($objHelperObject, $propertiesValues);
        $properties = Obj::getProperties($objHelperObject, null, Obj::PROPERTY_PRIVATE);
        $this->assertEquals($propertiesValues, $properties);
    }

    /**
     * Test set properties one not found.
     */
    public function testSetPropertiesOneNotFound()
    {
        $propertiesValues = [
            'property1' => md5(microtime(true)) . '1',
            'unknown' => md5(microtime(true)),
            'property3' => md5(microtime(true)) . '3',
            'property4' => md5(microtime(true)) . '4'
        ];
        $objHelperObject = new ObjHelperObject();
        $this->assertFalse(Obj::setProperties($objHelperObject, $propertiesValues));
    }

    /**
     * Test set properties empty.
     */
    public function testSetPropertiesEmpty()
    {
        $objHelperObject = new ObjHelperObject();
        $this->assertFalse(Obj::setProperties($objHelperObject, []));
    }

    /**
     * Test callMethod private static.
     */
    public function testCallMethodPrivateStatic()
    {
        $method = 'privateMethod';
        $check = Obj::callMethod($method, null, [], ObjHelperStatic::class);
        $this->assertEquals('(' . $method . ')', $check);
    }

    /**
     * Test callMethod private static.
     */
    public function testCallMethodPrivateStaticWithArguments()
    {
        $method = 'privateMethod';
        $check = Obj::callMethod($method, null, [
            'arguments' => '.test'
        ], ObjHelperStatic::class);
        $this->assertEquals('(' . $method . ').test', $check);
    }

    /**
     * Test getReflectionClass by object.
     */
    public function testGetReflectionClassByObject()
    {
        $objHelperObject = new ObjHelperObject();
        $reflectionClass = $this->getReflectionClassFromObj($objHelperObject);
        $this->assertEquals(ObjHelperObject::class, $reflectionClass->getName());
    }

    /**
     * Test getReflectionClass by class.
     */
    public function testGetReflectionClassByClass()
    {
        $reflectionClass = $this->getReflectionClassFromObj(ObjHelperObject::class);
        $this->assertEquals(ObjHelperObject::class, $reflectionClass->getName());
    }

    /**
     * Test getReflectionClass by object override.
     */
    public function testGetReflectionClassByByObjectOverride()
    {
        $objHelperObjectExtended = new ObjHelperObjectExtended();
        $reflectionClass = $this->getReflectionClassFromObj($objHelperObjectExtended, ObjHelperObject::class);
        $this->assertEquals(ObjHelperObject::class, $reflectionClass->getName());
    }

    /**
     * Test getReflectionClass by class override.
     */
    public function testGetReflectionClassByByClassOverride()
    {
        $reflectionClass = $this->getReflectionClassFromObj(ObjHelperObjectExtended::class, ObjHelperObject::class);
        $this->assertEquals(ObjHelperObject::class, $reflectionClass->getName());
    }

    /**
     * Test getReflectionMethod by object.
     * @throws ReflectionException
     */
    public function testGetReflectionMethodByObject()
    {
        $objHelperObject = new ObjHelperObject();
        $reflectionMethod = $this->getReflectionMethodFromObj('privateMethod', $objHelperObject);
        $this->assertEquals('privateMethod', $reflectionMethod->name);
        $this->assertEquals(ObjHelperObject::class, Obj::getProperty('class', $reflectionMethod));
    }

    /**
     * Test getReflectionMethod by class.
     * @throws ReflectionException
     */
    public function testGetReflectionMethodByClass()
    {
        $reflectionMethod = $this->getReflectionMethodFromObj('privateMethod', null, ObjHelperObject::class);
        $this->assertEquals('privateMethod', $reflectionMethod->name);
        $this->assertEquals(ObjHelperObject::class, Obj::getProperty('class', $reflectionMethod));
    }

    /**
     * Test getReflectionMethod by object override.
     * @throws ReflectionException
     */
    public function testGetReflectionMethodByByObjectOverride()
    {
        $objHelperObjectExtended = new ObjHelperObjectExtended();
        $reflectionMethod = $this->getReflectionMethodFromObj(
            'privateMethod',
            $objHelperObjectExtended,
            ObjHelperObject::class
        );
        $this->assertEquals('privateMethod', $reflectionMethod->name);
        $this->assertEquals(ObjHelperObject::class, Obj::getProperty('class', $reflectionMethod));
    }

    /**
     * Test getReflectionMethod by class override.
     * @throws ReflectionException
     */
    public function testGetReflectionMethodByByClassOverride()
    {
        $reflectionMethod = $this->getReflectionMethodFromObj('privateMethod', ObjHelperObjectExtended::class);
        $this->assertEquals('privateMethod', $reflectionMethod->name);
        $this->assertEquals(ObjHelperObject::class, Obj::getProperty('class', $reflectionMethod));
    }

    /**
     * Setup.
     */
    protected function setUp()
    {
        parent::setUp();

        // Load helper classes.
        require_once(__DIR__ . '/Helpers/ObjHelperObject.php');
        require_once(__DIR__ . '/Helpers/ObjHelperObjectExtended.php');
        require_once(__DIR__ . '/Helpers/ObjHelperStatic.php');
        require_once(__DIR__ . '/Helpers/ObjHelperInterface.php');
        require_once(__DIR__ . '/Helpers/ObjHelperWithInterface.php');
        require_once(__DIR__ . '/Helpers/ObjHelperWithoutInterface.php');
    }

    /**
     * Get reflection class from obj.
     *
     * @param object|string $objectOrClass
     * @param string $classOverride Default null which means class from $object.
     * @return ReflectionClass
     * @throws ReflectionException
     */
    private function getReflectionClassFromObj($objectOrClass, $classOverride = null)
    {
        $reflectionMethod = new ReflectionMethod(Obj::class, 'getReflectionClass');
        $reflectionMethod->setAccessible(true);
        return $reflectionMethod->invokeArgs(null, [$objectOrClass, $classOverride]);
    }

    /**
     * Get reflection method from obj.
     *
     * @param string $method
     * @param object|string $objectOrClass
     * @param string $classOverride Default null which means class from $object.
     * @return ReflectionClass
     * @throws ReflectionException
     */
    private function getReflectionMethodFromObj($method, $objectOrClass, $classOverride = null)
    {
        $reflectionMethod = new ReflectionMethod(Obj::class, 'getReflectionMethod');
        $reflectionMethod->setAccessible(true);
        return $reflectionMethod->invokeArgs(null, [$method, $objectOrClass, $classOverride]);
    }
}
