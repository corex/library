<?php

use CoRex\Support\System\Path;

class PathTest extends PHPUnit_Framework_TestCase
{
    private $rootDirectory;
    private $currentVendor;
    private $currentPackage;

    /**
     * Setup.
     */
    protected function setUp()
    {
        parent::setUp();

        // Prepare root directory.
        $this->rootDirectory = __DIR__;
        for ($c1 = 0; $c1 < 5; $c1++) {
            $this->rootDirectory = dirname($this->rootDirectory);
        }

        // Get package details.
        $packagePath = dirname(dirname(__DIR__));
        $this->currentPackage = basename($packagePath);
        $this->currentVendor = basename(dirname($packagePath));
    }

    /**
     * Test get root.
     */
    public function testGetRoot()
    {
        $this->assertEquals($this->rootDirectory, Path::getRoot());
        $this->assertEquals($this->rootDirectory . '/test1/test2', Path::getRoot(['test1', 'test2']));
    }

    /**
     * Test get package current.
     */
    public function testGetPackageCurrent()
    {
        $this->assertEquals(
            $this->rootDirectory . '/vendor/' . $this->currentVendor . '/' . $this->currentPackage,
            Path::getPackageCurrent()
        );
    }

    /**
     * Test get package.
     */
    public function testGetPackage()
    {
        $this->assertEquals(
            $this->rootDirectory . '/vendor/test1/test2',
            Path::getPackage('test1', 'test2')
        );
    }

    /**
     * Test get vendor name.
     */
    public function testGetVendorName()
    {
        $this->assertEquals($this->currentVendor, Path::getVendorName());
    }

    /**
     * Test get package name.
     */
    public function testGetPackageName()
    {
        $this->assertEquals($this->currentPackage, Path::getPackageName());
    }
}
