<?php

use CoRex\Support\Arr;

class ArrTest extends PHPUnit_Framework_TestCase
{
    private $actor1 = ['firstname' => 'Sean', 'lastname' => 'Connery'];
    private $actor2 = ['firstname' => 'Roger', 'lastname' => 'Moore'];
    private $actor3 = ['firstname' => 'Timothy', 'lastname' => 'Dalton'];
    private $actor4 = ['firstname' => 'Pierce', 'lastname' => 'Brosnan'];
    private $actor5 = ['firstname' => 'Daniel', 'lastname' => 'Craig'];

    /**
     * Test get.
     */
    public function testGet()
    {
        $data = ['actor' => $this->actor1];
        $this->assertEquals($this->actor1['firstname'], Arr::get($data, 'actor.firstname'));
        $this->assertEquals($this->actor1['lastname'], Arr::get($data, 'actor.lastname'));
        $this->assertEquals('test', Arr::get($data, 'actor.test', 'test'));
        $this->assertNull(Arr::get($data, 'actor.test'));
    }

    /**
     * Test set.
     */
    public function testSet()
    {
        $data = [];
        $this->assertNull(Arr::get($data, 'actor.test1.test2'));
        Arr::set($data, 'actor.test1.test2', 'testing', true);
        $this->assertEquals('testing', Arr::get($data, 'actor.test1.test2'));
    }

    /**
     * Test get first.
     */
    public function testGetFirst()
    {
        $data = [$this->actor1, $this->actor2, $this->actor3, $this->actor4, $this->actor5];
        $this->assertEquals($this->actor1, Arr::getFirst($data));
        $this->assertEquals($this->actor1['firstname'], Arr::getFirst($data, 'firstname'));
    }

    /**
     * Test get last.
     */
    public function testGetLast()
    {
        $data = [$this->actor1, $this->actor2, $this->actor3, $this->actor4, $this->actor5];
        $this->assertEquals($this->actor5, Arr::getLast($data));
        $this->assertEquals($this->actor5['firstname'], Arr::getLast($data, 'firstname'));
    }

    /**
     * Test remove last.
     */
    public function testRemoveLast()
    {
        $data = [$this->actor1, $this->actor2, $this->actor3, $this->actor4, $this->actor5];
        $data = Arr::removeLast($data);
        $this->assertEquals($this->actor4, Arr::getLast($data));
    }

    /**
     * Test is list.
     */
    public function testIsList()
    {
        $data = [
            'actor1' => $this->actor1,
            'actor2' => $this->actor2,
            'actor3' => $this->actor3,
            'actor4' => $this->actor4,
            'actor5' => $this->actor5
        ];
        $this->assertFalse(Arr::isList($data));
        $data = [$this->actor1, $this->actor2, $this->actor3, $this->actor4, $this->actor5];
        $this->assertTrue(Arr::isList($data));
    }

    /**
     * Test is string in list.
     */
    public function testIsStringInList()
    {
        $data = [4345, 435, 234, 43, 435, 345, 2354];
        $this->assertFalse(Arr::isStringInList($data));
        $data = [$this->actor1, $this->actor2, $this->actor3, $this->actor4, $this->actor5];
        $this->assertTrue(Arr::isStringInList($data, 'firstname'));
    }

    /**
     * Test index of simple.
     */
    public function testIndexOfSimple()
    {
        // Test simple array.
        $data = ['test1', 'test2', 'test3'];
        $this->assertEquals(1, Arr::indexOf($data, 'test2'));
    }

    /**
     * Test index of simple associative.
     */
    public function testIndexOfSimpleAssociative()
    {
        // Test simple array with associative item.
        $data = [$this->actor1, $this->actor2, $this->actor3, $this->actor4, $this->actor5];
        $this->assertEquals(1, Arr::indexOf($data, $this->actor2['firstname'], 'firstname'));
    }

    /**
     * Test index of associative.
     */
    public function testIndexOfAssociative()
    {
        // Test associative array.
        $data = ['test1' => 'test1', 'test2' => 'test2', 'test3' => 'test3'];
        $this->assertEquals('test2', Arr::indexOf($data, 'test2'));
    }

    /**
     * Test index of associative associative.
     */
    public function testIndexOfAssociativeAssociative()
    {
        // Test associative array with associative item.
        $data = [
            'actor1' => $this->actor1,
            'actor2' => $this->actor2,
            'actor3' => $this->actor3,
            'actor4' => $this->actor4,
            'actor5' => $this->actor5
        ];
        $this->assertEquals('actor2', Arr::indexOf($data, $this->actor2['firstname'], 'firstname'));
    }

    /**
     * Test keys exist.
     */
    public function testKeysExist()
    {
        $data = ['actor1' => 'test', 'actor2' => 'test', 'actor3' => 'test', 'actor4' => 'test'];
        $this->assertFalse(Arr::keysExist($data, ['unknown', 'actor3']));
        $this->assertTrue(Arr::keysExist($data, ['actor1', 'actor3']));
    }

    /**
     * Test keys.
     */
    public function testKeys()
    {
        $data = ['actor1' => 'test', 'actor2' => 'test', 'actor3' => 'test', 'actor4' => 'test'];
        $this->assertEquals(array_keys($data), Arr::keys($data));
    }

    /**
     * Test is associative.
     */
    public function testIsAssociative()
    {
        $data = ['test1', 'test2', 'test3'];
        $this->assertFalse(Arr::isAssociative($data));
        $data = ['actor1' => 'test', 'actor2' => 'test', 'actor3' => 'test', 'actor4' => 'test'];
        $this->assertTrue(Arr::isAssociative($data));
    }

    /**
     * Test pluck simple associative.
     */
    public function testPluckSimpleAssociative()
    {
        // Test simple array with associative item.
        $checkData = [$this->actor1['firstname'], $this->actor2['firstname']];
        $data = [$this->actor1, $this->actor2];
        $this->assertEquals($checkData, Arr::pluck($data, 'firstname'));
    }

    /**
     * Test pluck simple associative.
     */
    public function testPluckAssociative()
    {
        // Test associative array.
        $data = ['test1' => 'test1', 'test2' => 'test2', 'test3' => 'test3'];
        $this->assertEquals([null, null, null], Arr::pluck($data, 'test2'));
    }

    /**
     * Test get line match.
     */
    public function testGetLineMatch()
    {
        $lines = [
            '         use CoRex\Database\Command\CommandBase;                   ',
            '                          use CoRex\Database\Interfaces\ConnectorInterface;                ',
            'use CoRex\Support\System\Directory;               ',
            '            use CoRex\Support\System\Template;       '
        ];
        $linesMatch = [
            'CoRex\Database\Command\CommandBase',
            'CoRex\Database\Interfaces\ConnectorInterface',
            'CoRex\Support\System\Directory',
            'CoRex\Support\System\Template'
        ];
        $this->assertEquals($linesMatch, Arr::getLineMatch($lines, 'use ', ';', true, true));
    }
}
