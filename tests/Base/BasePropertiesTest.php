<?php

use PHPUnit\Framework\TestCase;

class BasePropertiesTest extends TestCase
{
    /**
     * Setup.
     */
    public function setUp()
    {
        parent::setUp();
        require_once(dirname(__DIR__) . '/Helpers/BasePropertiesHelper.php');
    }

    /**
     * Test constructor null.
     *
     * @throws ReflectionException
     */
    public function testConstructorNull()
    {
        $properties = new BasePropertiesHelper(null);
        $this->assertNull($properties->getPrivate());
        $this->assertNull($properties->getProtected());
        $this->assertNull($properties->publicValue);
    }

    /**
     * Test private value set.
     *
     * @throws ReflectionException
     */
    public function testGetPrivateValue()
    {
        $testValue = microtime();
        $properties = new BasePropertiesHelper([
            'privateValue' => $testValue
        ]);
        $this->assertEquals($testValue, $properties->getPrivate());
        $this->assertNull($properties->getProtected());
        $this->assertNull($properties->publicValue);
    }

    /**
     * Test protected value set.
     *
     * @throws ReflectionException
     */
    public function testProtectedValue()
    {
        $testValue = microtime();
        $properties = new BasePropertiesHelper([
            'protectedValue' => $testValue
        ]);
        $this->assertNull($properties->getPrivate());
        $this->assertEquals($testValue, $properties->getProtected());
        $this->assertNull($properties->publicValue);
    }

    /**
     * Test public value set.
     *
     * @throws ReflectionException
     */
    public function testPublicValue()
    {
        $testValue = microtime();
        $properties = new BasePropertiesHelper([
            'publicValue' => $testValue
        ]);
        $this->assertNull($properties->getPrivate());
        $this->assertNull($properties->getProtected());
        $this->assertEquals($testValue, $properties->publicValue);
    }
}
