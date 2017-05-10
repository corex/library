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
     */
    public function testGetPrivatePropertiesFromObject()
    {
        require_once(__DIR__ . '/Helpers/ObjHelperObject.php');
        $objHelperObject = new ObjHelperObject();
        $properties = Obj::getPropertiesFromObject(Obj::PROPERTY_PRIVATE, $objHelperObject);
        $this->assertEquals($this->checkProperties, $properties);
    }

    /**
     * Test get private properties from static.
     */
    public function testGetPrivatePropertiesFromStatic()
    {
        require_once(__DIR__ . '/Helpers/ObjHelperStatic.php');
        $properties = Obj::getPropertiesFromStatic(Obj::PROPERTY_PRIVATE, ObjHelperStatic::class);
        $this->assertEquals($this->checkProperties, $properties);
    }

    /**
     * Test get interfaces with.
     */
    public function testGetInterfacesWith()
    {
        $this->loadClasses();
        $objHelperWithInterface = new ObjHelperWithInterface();
        $interfaces = Obj::getInterfaces($objHelperWithInterface);
        $this->assertArrayHasKey(ObjHelperInterface::class, $interfaces);
    }

    /**
     * Test get interfaces without.
     */
    public function testGetInterfacesWithout()
    {
        $this->loadClasses();
        $objHelperWithoutInterface = new ObjHelperWithoutInterface();
        $interfaces = Obj::getInterfaces($objHelperWithoutInterface);
        $this->assertArrayNotHasKey(ObjHelperInterface::class, $interfaces);
    }

    /**
     * Test has interface with.
     */
    public function testHasInterfaceWith()
    {
        $this->loadClasses();
        $objHelperWithInterface = new ObjHelperWithInterface();
        $this->assertTrue(Obj::hasInterface($objHelperWithInterface, ObjHelperInterface::class));
    }

    /**
     * Test has interface without.
     */
    public function testHasInterfaceWithout()
    {
        $this->loadClasses();
        $objHelperWithoutInterface = new ObjHelperWithoutInterface();
        $this->assertFalse(Obj::hasInterface($objHelperWithoutInterface, ObjHelperInterface::class));
    }

    /**
     * Test set property.
     */
    public function testSetPropertyFound()
    {
        require_once(__DIR__ . '/Helpers/ObjHelperObject.php');
        $check1 = md5(microtime(true)) . '1';
        $check2 = md5(microtime(true)) . '2';
        $check3 = md5(microtime(true)) . '3';
        $check4 = md5(microtime(true)) . '4';

        $objHelperObject = new ObjHelperObject();
        $this->assertTrue(Obj::setProperty($objHelperObject, 'property1', $check1));
        $this->assertTrue(Obj::setProperty($objHelperObject, 'property2', $check2));
        $this->assertTrue(Obj::setProperty($objHelperObject, 'property3', $check3));
        $this->assertTrue(Obj::setProperty($objHelperObject, 'property4', $check4));

        $properties = Obj::getPropertiesFromObject(Obj::PROPERTY_PRIVATE, $objHelperObject);
        $this->assertEquals($check1, $properties['property1']);
        $this->assertEquals($check2, $properties['property2']);
        $this->assertEquals($check3, $properties['property3']);
        $this->assertEquals($check4, $properties['property4']);
    }

    /**
     * Test set property not found.
     */
    public function testSetPropertyNotFound()
    {
        require_once(__DIR__ . '/Helpers/ObjHelperObject.php');
        $check1 = md5(microtime(true));
        $objHelperObject = new ObjHelperObject();
        $this->assertFalse(Obj::setProperty($objHelperObject, 'unknown', $check1));
    }

    /**
     * Test get property not found.
     */
    public function testGetPropertyNotFound()
    {
        require_once(__DIR__ . '/Helpers/ObjHelperObject.php');
        $check = md5(microtime(true));
        $objHelperObject = new ObjHelperObject();
        $property = Obj::getProperty($objHelperObject, 'unknown', $check);
        $this->assertEquals($check, $property);
    }

    /**
     * Test get property found.
     */
    public function testGetPropertyFound()
    {
        require_once(__DIR__ . '/Helpers/ObjHelperObject.php');
        $check1 = md5(microtime(true));
        $check2 = md5(microtime(true));
        $objHelperObject = new ObjHelperObject();
        Obj::setProperty($objHelperObject, 'property1', $check1);
        Obj::setProperty($objHelperObject, 'property2', $check2);
        $this->assertEquals($check1, Obj::getProperty($objHelperObject, 'property1'));
        $this->assertEquals($check2, Obj::getProperty($objHelperObject, 'property2'));
    }

    /**
     * Test set properties.
     */
    public function testSetPropertiesFound()
    {
        require_once(__DIR__ . '/Helpers/ObjHelperObject.php');
        $propertiesValues = [
            'property1' => md5(microtime(true)) . '1',
            'property2' => md5(microtime(true)) . '2',
            'property3' => md5(microtime(true)) . '3',
            'property4' => md5(microtime(true)) . '4'
        ];
        $objHelperObject = new ObjHelperObject();
        Obj::setProperties($objHelperObject, $propertiesValues);
        $properties = Obj::getPropertiesFromObject(Obj::PROPERTY_PRIVATE, $objHelperObject);
        $this->assertEquals($propertiesValues, $properties);
    }

    /**
     * Test set properties one not found.
     */
    public function testSetPropertiesOneNotFound()
    {
        require_once(__DIR__ . '/Helpers/ObjHelperObject.php');
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
     * Load classes.
     */
    private function loadClasses()
    {
        require_once(__DIR__ . '/Helpers/ObjHelperInterface.php');
        require_once(__DIR__ . '/Helpers/ObjHelperWithInterface.php');
        require_once(__DIR__ . '/Helpers/ObjHelperWithoutInterface.php');
    }
}
