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
     * Load classes.
     */
    private function loadClasses()
    {
        require_once(__DIR__ . '/Helpers/ObjHelperInterface.php');
        require_once(__DIR__ . '/Helpers/ObjHelperWithInterface.php');
        require_once(__DIR__ . '/Helpers/ObjHelperWithoutInterface.php');
    }
}
