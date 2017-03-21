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
    public function testRoot()
    {
        $this->assertEquals($this->rootDirectory, Path::root());
        $this->assertEquals($this->rootDirectory . '/test1/test2', Path::root(['test1', 'test2']));
    }

    /**
     * Test get package current.
     */
    public function testGetPackageCurrent()
    {
        $this->assertEquals(
            $this->rootDirectory . '/vendor/' . $this->currentVendor . '/' . $this->currentPackage,
            Path::packageCurrent()
        );
    }

    /**
     * Test get package.
     */
    public function testGetPackage()
    {
        $this->assertEquals(
            $this->rootDirectory . '/vendor/test1/test2',
            Path::package('test1', 'test2')
        );
    }

    /**
     * Test get vendor name.
     */
    public function testGetVendorName()
    {
        $this->assertEquals($this->currentVendor, Path::vendorName());
    }

    /**
     * Test get package name.
     */
    public function testGetPackageName()
    {
        $this->assertEquals($this->currentPackage, Path::packageName());
    }
}
