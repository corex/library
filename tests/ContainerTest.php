<?php

use CoRex\Support\Container;
use CoRex\Support\Obj;
use PHPUnit\Framework\TestCase;

class ContainerTest extends TestCase
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
     * @throws ReflectionException
     */
    public function testConstructorNull()
    {
        $container = new Container();
        $this->assertEquals([], Obj::getProperty('properties', $container));
    }

    /**
     * Test constructor no data.
     */
    public function testConstructorNoData()
    {
        $container = new Container();
        $this->assertEquals([], $container->toArray());
    }

    /**
     * Test constructor with data.
     */
    public function testConstructorWithData()
    {
        $container = new Container($this->data);
        $this->assertEquals($this->data, $container->toArray());
    }

    /**
     * Test clear no data.
     */
    public function testClearNoData()
    {
        $container = new Container();
        $this->assertEquals([], $container->toArray());
    }

    /**
     * Test clear with data.
     */
    public function testClearWithData()
    {
        $container = new Container();
        $container->clear($this->data);
        $this->assertEquals($this->data, $container->toArray());
    }

    /**
     * Test has no data.
     * @throws Exception
     */
    public function testHasNoData()
    {
        $container = new Container();
        $this->assertFalse($container->has('actor.firstname'));
    }

    /**
     * Test has with data.
     * @throws Exception
     */
    public function testHasWithData()
    {
        $container = new Container();
        $container->clear($this->data);
        $this->assertTrue($container->has('actor.firstname'));
    }

    /**
     * Test set.
     * @throws Exception
     */
    public function testSet()
    {
        $container = new Container($this->data);

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
        $container = new Container();
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
        $container = new Container($this->data);

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
        $container = new Container($this->data);

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
     * Test to json.
     */
    public function testToJson()
    {
        $container = new Container($this->data);
        $this->assertEquals(json_encode($this->data), $container->toJson(false));
        $this->assertEquals(json_encode($this->data, JSON_PRETTY_PRINT), $container->toJson(true));
    }

    /**
     * Test to array.
     */
    public function testToArray()
    {
        $container = new Container($this->data);
        $this->assertEquals($this->data, $container->toArray());
    }
}
