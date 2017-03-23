<?php

use PHPUnit\Framework\TestCase;

class PropertiesTest extends TestCase
{
    /**
     * Setup.
     */
    public function setUp()
    {
        parent::setUp();
        require_once(__DIR__ . '/Helpers/PropertiesHelper.php');
    }

    /**
     * Test constructor null.
     */
    public function testConstructorNull()
    {
        $properties = new PropertiesHelper(null);
        $this->assertNull($properties->getPrivate());
        $this->assertNull($properties->getProtected());
        $this->assertNull($properties->publicValue);
    }

    /**
     * Test private value set.
     */
    public function testGetPrivateValue()
    {
        $testValue = microtime();
        $properties = new PropertiesHelper([
            'privateValue' => $testValue
        ]);
        $this->assertEquals($testValue, $properties->getPrivate());
        $this->assertNull($properties->getProtected());
        $this->assertNull($properties->publicValue);
    }

    /**
     * Test protected value set.
     */
    public function testProtectedValue()
    {
        $testValue = microtime();
        $properties = new PropertiesHelper([
            'protectedValue' => $testValue
        ]);
        $this->assertNull($properties->getPrivate());
        $this->assertEquals($testValue, $properties->getProtected());
        $this->assertNull($properties->publicValue);
    }

    /**
     * Test public value set.
     */
    public function testPublicValue()
    {
        $testValue = microtime();
        $properties = new PropertiesHelper([
            'publicValue' => $testValue
        ]);
        $this->assertNull($properties->getPrivate());
        $this->assertNull($properties->getProtected());
        $this->assertEquals($testValue, $properties->publicValue);
    }
}
