<?php

use CoRex\Support\Collection;
use CoRex\Support\Obj;
use PHPUnit\Framework\TestCase;

class CollectionTest extends TestCase
{
    private $actor1 = ['firstname' => 'Sean', 'lastname' => 'Connery', 'value' => 1];
    private $actor2 = ['firstname' => 'Roger', 'lastname' => 'Moore', 'value' => 2];
    private $actor3 = ['firstname' => 'Timothy', 'lastname' => 'Dalton', 'value' => 3];
    private $actor4 = ['firstname' => 'Pierce', 'lastname' => 'Brosnan', 'value' => 4];
    private $actor5 = ['firstname' => 'Daniel', 'lastname' => 'Craig', 'value' => 5];
    private $data;

    /**
     * @var Collection
     */
    private $collection;

    /**
     * Setup.
     */
    protected function setUp()
    {
        parent::setUp();
        $this->data = [$this->actor1, $this->actor2, $this->actor3, $this->actor4, $this->actor5];
        $this->collection = new Collection($this->data);
    }

    /**
     * Test constructor null.
     *
     * @throws Exception
     * @throws ReflectionException
     */
    public function testConstructorNull()
    {
        $collection = new Collection();
        $this->assertEquals([], Obj::getProperty('items', $collection));
    }

    /**
     * Test constructor array.
     *
     * @throws Exception
     * @throws ReflectionException
     */
    public function testConstructorArray()
    {
        $collection = new Collection($this->data);
        $this->assertEquals($this->data, Obj::getProperty('items', $collection));
    }

    /**
     * Test constructor other than array.
     */
    public function testConstructorOtherThanArray()
    {
        $this->expectException(TypeError::class);
        new Collection('testing');
    }

    /**
     * To json.
     */
    public function testToJson()
    {
        $this->assertEquals(json_encode($this->data), $this->collection->toJson());
    }

    /**
     * To json.
     */
    public function testToJsonPrettyPrint()
    {
        $this->assertEquals(json_encode($this->data, JSON_PRETTY_PRINT), $this->collection->toJson(true));
    }

    /**
     * Test count.
     */
    public function testCount()
    {
        $this->assertEquals(5, $this->collection->count());
    }

    /**
     * Test count 0.
     */
    public function testCountZero()
    {
        Obj::setProperty('items', $this->collection, null);
        $this->assertEquals(0, $this->collection->count());
    }

    /**
     * Test current.
     */
    public function testCurrent()
    {
        $this->collection->next()->next();
        $this->assertEquals($this->actor3, $this->collection->current());
    }

    /**
     * Test current null.
     */
    public function testCurrentNull()
    {
        Obj::setProperty('items', $this->collection, null);
        $this->assertNull($this->collection->current());
    }

    /**
     * Test next.
     */
    public function testNext()
    {
        $this->testCurrent();
    }

    /**
     * Test next null.
     */
    public function testNextNull()
    {
        Obj::setProperty('items', $this->collection, null);
        $this->assertNull($this->collection->next());
    }

    /**
     * Test key.
     */
    public function testKey()
    {
        $this->collection->next()->next();
        $this->assertEquals(2, $this->collection->key());
    }

    /**
     * Test key null.
     */
    public function testKeyNull()
    {
        Obj::setProperty('items', $this->collection, null);
        $this->assertNull($this->collection->key());
    }

    /**
     * Test valid.
     */
    public function testValid()
    {
        $this->collection->next()->next()->next()->next();
        $this->assertTrue($this->collection->valid());
        $this->collection->next();
        $this->assertFalse($this->collection->valid());
    }

    /**
     * Test valid null.
     */
    public function testValidNull()
    {
        Obj::setProperty('items', $this->collection, null);
        $this->assertFalse($this->collection->valid());
    }

    /**
     * Test rewind.
     */
    public function testRewind()
    {
        $this->collection->next()->next()->rewind();
        $this->assertEquals($this->actor1, $this->collection->current());
    }

    /**
     * Test rewind null.
     */
    public function testRewindNull()
    {
        Obj::setProperty('items', $this->collection, null);
        $this->assertNull($this->collection->rewind());
    }

    /**
     * Test first.
     */
    public function testFirst()
    {
        $this->collection->next()->next()->first();
        $this->assertEquals($this->actor1, $this->collection->next()->next()->first());
    }

    /**
     * Test last.
     */
    public function testLast()
    {
        $this->collection->next()->next()->first();
        $this->assertEquals($this->actor5, $this->collection->next()->next()->last());
    }

    /**
     * Test last.
     */
    public function testLastNull()
    {
        Obj::setProperty('items', $this->collection, null);
        $this->assertNull($this->collection->last());
    }

    /**
     * Test all.
     */
    public function testAll()
    {
        $this->assertEquals($this->data, $this->collection->all());
    }

    /**
     * Test each.
     *
     * @throws Exception
     */
    public function testEach()
    {
        $check = [
            $this->actor1['firstname'],
            $this->actor2['firstname'],
            $this->actor3['firstname'],
            $this->actor4['firstname'],
            $this->actor5['firstname']
        ];
        $this->collection->each(function (&$item) {
            $item = $item['firstname'];
        });
        $this->assertEquals($check, $this->collection->all());
    }

    /**
     * Test each.
     *
     * @throws Exception
     */
    public function testEachBreak()
    {
        $this->collection->each(function (&$item) {
            return false;
        });
        $this->assertEquals($this->data, $this->collection->all());
    }

    /**
     * Test pluck.
     */
    public function testPluck()
    {
        $check = [
            $this->actor1['firstname'],
            $this->actor2['firstname'],
            $this->actor3['firstname'],
            $this->actor4['firstname'],
            $this->actor5['firstname']
        ];
        $this->assertEquals($check, $this->collection->pluck('firstname')->all());
    }

    /**
     * Test pluck object.
     */
    public function testPluckObject()
    {
        $data = [];

        $actor1 = new stdClass();
        $actor1->value = 'test1';
        $data[] = $actor1;

        $actor2 = new stdClass();
        $actor2->value = 'test2';
        $data[] = $actor2;

        Obj::setProperty('items', $this->collection, $data);
        $this->assertEquals(['test1', 'test2'], $this->collection->pluck('value')->all());
    }

    /**
     * Test pluck null.
     */
    public function testPluckNull()
    {
        Obj::setProperty('items', $this->collection, null);
        $this->assertNull($this->collection->pluck('firstname'));
    }

    /**
     * Test sum.
     */
    public function testSum()
    {
        $this->assertEquals(15, $this->collection->sum('value'));
    }

    /**
     * Test average.
     */
    public function testAverage()
    {
        $this->assertEquals(3, $this->collection->average('value'));
    }

    /**
     * Test average default value.
     */
    public function testAverageDefaultValue()
    {
        Obj::setProperty('items', $this->collection, null);
        $this->assertEquals(0, $this->collection->average('value'));
    }

    /**
     * Test average sum zero.
     */
    public function testAverageSumZero()
    {
        $data = [
            ['value' => 0],
            ['value' => 0]
        ];
        Obj::setProperty('items', $this->collection, $data);
        $this->assertEquals(0, $this->collection->average('value'));
    }

    /**
     * Test max.
     */
    public function testMax()
    {
        $this->assertEquals(5, $this->collection->max('value'));
    }

    /**
     * Test max.
     */
    public function testMaxNull()
    {
        Obj::setProperty('items', $this->collection, null);
        $this->assertEquals(0, $this->collection->max('value'));
    }

    /**
     * Test min.
     */
    public function testMin()
    {
        $this->assertEquals(1, $this->collection->min('value'));
    }

    /**
     * Test min.
     */
    public function testMinNull()
    {
        Obj::setProperty('items', $this->collection, null);
        $this->assertEquals(0, $this->collection->min('value'));
    }

    /**
     * Test reverse.
     */
    public function testReverse()
    {
        $this->assertEquals(array_reverse($this->data), $this->collection->reverse()->values());
    }

    /**
     * Test keys.
     */
    public function testKeys()
    {
        $this->assertEquals([0, 1, 2, 3, 4], $this->collection->keys());
    }

    /**
     * Test values.
     */
    public function testValues()
    {
        $this->assertEquals($this->data, $this->collection->values());
    }

    /**
     * Test get.
     */
    public function testGet()
    {
        $this->assertEquals($this->actor3, $this->collection->get(2));
    }

    /**
     * Test get default value.
     */
    public function testGetDefaultValue()
    {
        $check = md5(mt_rand(1, 100000));
        Obj::setProperty('items', $this->collection, null);
        $this->assertEquals($check, $this->collection->get(2, $check));
    }

    /**
     * Test has.
     */
    public function testHas()
    {
        $this->assertTrue($this->collection->has(2));
    }

    /**
     * Test add.
     */
    public function testAdd()
    {
        $data = [$this->actor1, $this->actor2, $this->actor3, $this->actor4];
        $collection = new Collection($data);
        $this->assertEquals($this->data, $collection->add($this->actor5)->all());
    }

    /**
     * Test set.
     */
    public function testSet()
    {
        $data = $this->data;
        $data[2] = 'testing';
        $collection = new Collection($data);
        $collection->set(2, $this->actor3);
        $this->assertEquals($this->data, $collection->all());
    }

    /**
     * Test delete.
     */
    public function testDelete()
    {
        $data = [$this->actor1, $this->actor2, $this->actor4, $this->actor5];
        $this->collection->delete(2);
        $this->assertEquals($data, $this->collection->values());
    }
}
