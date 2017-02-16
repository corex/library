<?php

use CoRex\Support\Container;
use CoRex\Support\System\File;

class ContainerTest extends PHPUnit_Framework_TestCase
{
    private $data = [
        'actor' => [
            'firstname' => 'Roger',
            'lastname' => 'Moore'
        ]
    ];

    /**
     * Test constructor.
     */
    public function testConstructor()
    {
        // Check no data.
        $container = new Container();
        $this->assertEquals([], $container->toArray());

        // Check with data.
        $container = new Container($this->data);
        $this->assertEquals($this->data, $container->toArray());
    }

    /**
     * Test clear.
     */
    public function testClear()
    {
        $container = new Container();

        // Check no data.
        $this->assertEquals([], $container->toArray());

        // Check with data.
        $container->clear($this->data);
        $this->assertEquals($this->data, $container->toArray());
    }

    /**
     * Test exist.
     */
    public function testExist()
    {
        $container = new Container();

        // Create container with no data.
        $this->assertFalse($container->exist('actor.firstname'));

        // Create container with data.
        $container->clear($this->data);
        $this->assertTrue($container->exist('actor.firstname'));
    }

    /**
     * Test set.
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
     */
    public function testGet()
    {
        $container = new Container($this->data);

        // Check standard data.
        $this->assertEquals($this->data['actor']['firstname'], $container->get('actor.firstname'));
        $this->assertEquals($this->data['actor']['lastname'], $container->get('actor.lastname'));
    }

    /**
     * Test delete.
     */
    public function testDelete()
    {
        $container = new Container($this->data);

        // Check standard data.
        $this->assertEquals($this->data['actor']['firstname'], $container->get('actor.firstname'));
        $this->assertEquals($this->data['actor']['lastname'], $container->get('actor.lastname'));

        // Delete data.
        $container->delete('actor.firstname');

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

    /**
     * Test load json.
     */
    public function testLoadJson()
    {
        $filename = File::getTempFilename('', 'json');
        file_put_contents($filename, json_encode($this->data));
        $container = new Container();
        $container->loadJson($filename);
        $this->assertEquals($this->data, $container->toArray());
        if (file_exists($filename)) {
            unlink($filename);
        }
    }

    /**
     * Test save json.
     */
    public function testSaveJson()
    {
        $filename = File::getTempFilename('', 'json');
        $container = new Container($this->data);
        $container->saveJson($filename);
        $this->assertEquals(json_encode($this->data), file_get_contents($filename));
    }
}
