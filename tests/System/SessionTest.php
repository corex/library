<?php

use CoRex\Support\System\Session;

class SessionTest extends PHPUnit_Framework_TestCase
{
    private $namespace1 = 'Namespace 1';
    private $namespace2 = 'Namespace 2';
    private $testName1 = 'test name 1';
    private $testName2 = 'test name 2';
    private $testValue1 = 'test value 1';
    private $testValue2 = 'test value 2';

    /**
     * Test Clear.
     */
    public function testClear()
    {
        // Set test data for non namespace and for namespace.
        Session::set($this->testName1, $this->testValue1);
        Session::set($this->testName2, $this->testValue2, $this->namespace2);

        // Test values set.
        $this->assertEquals($this->testValue1, Session::get($this->testName1));
        $this->assertEquals($this->testValue2, Session::get($this->testName2, null, $this->namespace2));

        // Clear default session and check values.
        Session::clear();
        $this->assertNull(Session::get($this->testName1));
        $this->assertEquals($this->testValue2, Session::get($this->testName2, null, $this->namespace2));

        // Clear default session and check values.
        Session::clear($this->namespace2);
        $this->assertNull(Session::get($this->testName1));
        $this->assertNull(Session::get($this->testName2, null, $this->namespace2));
    }

    /**
     * Test set.
     */
    public function testSet()
    {
        // Test integer.
        Session::set('test', 4);
        $this->assertTrue(is_int(Session::get('test')));

        // Test string.
        Session::set('test', 'test');
        $this->assertTrue(is_string(Session::get('test')));

        // Test array.
        Session::set('test', ['test']);
        $this->assertTrue(is_array(Session::get('test')));

        // Test boolean.
        Session::set('test', false);
        $this->assertTrue(is_bool(Session::get('test')));

        // Test float.
        Session::set('test', 10.4);
        $this->assertTrue(is_float(Session::get('test')));

        // Test object.
        Session::set('test', new stdClass());
        $this->assertTrue(is_object(Session::get('test')));

        // Test null.
        Session::set('test', null);
        $this->assertNull(Session::get('test', 'test'));
    }

    /**
     * Test get.
     */
    public function testGet()
    {
        $this->testSet();
    }

    /**
     * Test get array.
     */
    public function testGetArray()
    {
        Session::clear();
        Session::set($this->testName1, $this->testValue1);
        Session::set($this->testName2, $this->testValue2);
        $testArray = [
            $this->testName1 => $this->testValue1,
            $this->testName2 => $this->testValue2
        ];
        $this->assertEquals($testArray, Session::getArray());
    }

    /**
     * Test has.
     */
    public function testHas()
    {
        Session::clear();
        $this->assertFalse(Session::has('test'));
        Session::set('test', 'test');
        $this->assertTrue(Session::has('test'));
    }

    /**
     * Test delete.
     */
    public function testDelete()
    {
        Session::clear();
        Session::set('test', 'test');
        $this->assertTrue(Session::has('test'));
        Session::delete('test');
        $this->assertFalse(Session::has('test'));
    }

    /**
     * Test page clear.
     */
    public function testPageClear()
    {
        // Set test data for non namespace and for namespace.
        Session::pageSet($this->testName1, $this->testValue1, $this->namespace1);
        Session::pageSet($this->testName2, $this->testValue2, $this->namespace2);

        // Test values set.
        $this->assertEquals($this->testValue1, Session::pageGet($this->testName1, null, $this->namespace1));
        $this->assertEquals($this->testValue2, Session::pageGet($this->testName2, null, $this->namespace2));

        // Clear default session and check values.
        Session::pageClear($this->namespace1);
        $this->assertNull(Session::pageGet($this->testName1, null, $this->namespace1));
        $this->assertEquals($this->testValue2, Session::pageGet($this->testName2, null, $this->namespace2));

        // Clear default session and check values.
        Session::pageClear($this->namespace2);
        $this->assertNull(Session::pageGet($this->testName1, null, $this->namespace1));
        $this->assertNull(Session::pageGet($this->testName2, null, $this->namespace2));
    }

    /**
     * Test page set.
     */
    public function testPageSet()
    {
        // Test integer.
        Session::pageSet('test', 4, $this->namespace1);
        $this->assertTrue(is_int(Session::pageGet('test', null, $this->namespace1)));

        // Test string.
        Session::pageSet('test', 'test', $this->namespace1);
        $this->assertTrue(is_string(Session::pageGet('test', null, $this->namespace1)));

        // Test array.
        Session::pageSet('test', ['test'], $this->namespace1);
        $this->assertTrue(is_array(Session::pageGet('test', null, $this->namespace1)));

        // Test boolean.
        Session::pageSet('test', false, $this->namespace1);
        $this->assertTrue(is_bool(Session::pageGet('test', null, $this->namespace1)));

        // Test float.
        Session::pageSet('test', 10.4, $this->namespace1);
        $this->assertTrue(is_float(Session::pageGet('test', null, $this->namespace1)));

        // Test object.
        Session::pageSet('test', new stdClass(), $this->namespace1);
        $this->assertTrue(is_object(Session::pageGet('test', null, $this->namespace1)));

        // Test null.
        Session::pageSet('test', null, $this->namespace1);
        $this->assertNull(Session::pageGet('test', 'test', $this->namespace1));
    }

    /**
     * Test page get.
     */
    public function testPageGet()
    {
        $this->testPageSet();
    }

    /**
     * Test page get array.
     */
    public function testPageGetArray()
    {
        Session::pageClear($this->namespace1);
        Session::pageSet($this->testName1, $this->testValue1, $this->namespace1);
        Session::pageSet($this->testName2, $this->testValue2, $this->namespace1);
        $testArray = [
            $this->testName1 => $this->testValue1,
            $this->testName2 => $this->testValue2
        ];
        $this->assertEquals($testArray, Session::pageGetArray($this->namespace1));
    }

    /**
     * Test page has.
     */
    public function testPageHas()
    {
        Session::pageClear($this->namespace1);
        $this->assertFalse(Session::pageHas('test', $this->namespace1));
        Session::pageSet('test', 'test', $this->namespace1);
        $this->assertTrue(Session::pageHas('test', $this->namespace1));
    }

    /**
     * Test page delete.
     */
    public function testPageDelete()
    {
        Session::pageClear($this->namespace1);
        Session::pageSet('test', 'test', $this->namespace1);
        $this->assertTrue(Session::pageHas('test', $this->namespace1));
        Session::pageDelete('test', $this->namespace1);
        $this->assertFalse(Session::pageHas('test', $this->namespace1));
    }
}