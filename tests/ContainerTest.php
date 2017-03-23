<?php

use CoRex\Support\Container;
use CoRex\Support\System\Directory;
use CoRex\Support\System\File;
use PHPUnit\Framework\TestCase;

class ContainerTest extends TestCase
{
    private $tempDirectory;

    private $data = [
        'actor' => [
            'firstname' => 'Roger',
            'lastname' => 'Moore'
        ]
    ];

    /**
     * Setup.
     */
    protected function setUp()
    {
        parent::setUp();
        $this->tempDirectory = sys_get_temp_dir();
        $this->tempDirectory .= '/' . str_replace('.', '', microtime(true));
        Directory::make($this->tempDirectory);
    }

    /**
     * Tear down.
     */
    protected function tearDown()
    {
        parent::tearDown();
        Directory::delete($this->tempDirectory);
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
     * Test exist no data.
     */
    public function testExistNoData()
    {
        $container = new Container();
        $this->assertFalse($container->exist('actor.firstname'));
    }

    /**
     * Test exist with data.
     */
    public function testExistWithData()
    {
        $container = new Container();
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
        $filename = File::getTempFilename($this->tempDirectory, '', 'json');
        File::put($filename, json_encode($this->data));
        $container = new Container();
        $container->getJson($filename);
        $this->assertEquals($this->data, $container->toArray());
    }

    /**
     * Test save json.
     */
    public function testSaveJson()
    {
        $filename = File::getTempFilename($this->tempDirectory, '', 'json');
        $container = new Container($this->data);
        $container->putJson($filename);
        $this->assertEquals(json_encode($this->data), file_get_contents($filename));
    }
}
