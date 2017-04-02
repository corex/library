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
        $properties = Obj::getPrivatePropertiesFromObject($objHelperObject);
        $this->assertEquals($this->checkProperties, $properties);
    }

    /**
     * Test get private properties from static.
     */
    public function testGetPrivatePropertiesFromStatic()
    {
        require_once(__DIR__ . '/Helpers/ObjHelperStatic.php');
        $properties = Obj::getPrivatePropertiesFromStatic(ObjHelperStatic::class);
        $this->assertEquals($this->checkProperties, $properties);
    }
}
