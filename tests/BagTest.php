<?php

use CoRex\Support\Bag;
use CoRex\Support\Obj;
use PHPUnit\Framework\TestCase;

class BagTest extends TestCase
{
    private $data = [
        'actor' => [
            'firstname' => 'Roger',
            'lastname' => 'Moore'
        ]
    ];

    /**
     * Test constructor no data.
     *
     * @throws Exception
     */
    public function testConstructorNull()
    {
        $container = new Bag(null);
        $this->assertEquals([], Obj::getProperty('properties', $container));
    }

    /**
     * Test constructor no data.
     */
    public function testConstructorNoData()
    {
        $container = new Bag();
        $this->assertEquals([], $container->all());
    }

    /**
     * Test constructor with data.
     */
    public function testConstructorWithData()
    {
        $container = new Bag($this->data);
        $this->assertEquals($this->data, $container->all());
    }

    /**
     * Test clear no data.
     */
    public function testClearNoData()
    {
        $container = new Bag();
        $this->assertEquals([], $container->all());
    }

    /**
     * Test clear null.
     */
    public function testClearNull()
    {
        $container = new Bag();
        $container->clear(null);
        $this->assertEquals([], $container->all());
    }

    /**
     * Test clear with data.
     */
    public function testClearWithData()
    {
        $container = new Bag();
        $container->clear($this->data);
        $this->assertEquals($this->data, $container->all());
    }

    /**
     * Test has no data.
     * @throws Exception
     */
    public function testHasNoData()
    {
        $container = new Bag();
        $this->assertFalse($container->has('actor.firstname'));
    }

    /**
     * Test has with data.
     * @throws Exception
     */
    public function testHasWithData()
    {
        $container = new Bag();
        $container->clear($this->data);
        $this->assertTrue($container->has('actor.firstname'));
    }

    /**
     * Test set.
     * @throws Exception
     */
    public function testSet()
    {
        $container = new Bag($this->data);

        // Check standard data.
        $this->assertEquals($this->data['actor']['firstname'], $container->get('actor.firstname'));
        $this->assertEquals($this->data['actor']['lastname'], $container->get('actor.lastname'));

        // Swap data.
        $container->set('actor.firstname', $this->data['actor']['lastname']);
        $container->set('actor.lastname', $this->data['actor']['firstname']);

        // Check swapped data.
        $this->assertEquals($this->data['actor']['lastname'], $container->get('actor.firstname'));
        $this->assertEquals($this->data['actor']['firstname'], $container->get('actor.lastname'));
    }

    /**
     * Test set array.
     * @throws Exception
     */
    public function testSetArray()
    {
        $container = new Bag();
        $container->setArray($this->data);

        // Check standard data.
        $this->assertEquals($this->data['actor']['firstname'], $container->get('actor.firstname'));
        $this->assertEquals($this->data['actor']['lastname'], $container->get('actor.lastname'));
    }

    /**
     * Test get.
     * @throws Exception
     */
    public function testGet()
    {
        $container = new Bag($this->data);

        // Check standard data.
        $this->assertEquals($this->data['actor']['firstname'], $container->get('actor.firstname'));
        $this->assertEquals($this->data['actor']['lastname'], $container->get('actor.lastname'));
    }

    /**
     * Test remove.
     * @throws Exception
     */
    public function testRemove()
    {
        $container = new Bag($this->data);

        // Check standard data.
        $this->assertEquals($this->data['actor']['firstname'], $container->get('actor.firstname'));
        $this->assertEquals($this->data['actor']['lastname'], $container->get('actor.lastname'));

        // Delete data.
        $container->remove('actor.firstname');

        // Check standard data.
        $this->assertNull($container->get('actor.firstname'));
        $this->assertEquals($this->data['actor']['lastname'], $container->get('actor.lastname'));
    }

    /**
     * Test keys.
     */
    public function testKeys()
    {
        $container = new Bag($this->data);
        $all = $container->all();
        $this->assertEquals(array_keys($all), $container->keys());
    }

    /**
     * Test all.
     */
    public function testAll()
    {
        $container = new Bag($this->data);
        $this->assertEquals($this->data, $container->all());
    }
}
