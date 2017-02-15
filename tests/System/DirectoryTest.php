<?php

use CoRex\Support\System\Directory;

class DirectoryTest extends PHPUnit_Framework_TestCase
{
    private $tempDirectory;

    /**
     * Setup.
     */
    protected function setUp()
    {
        parent::setUp();
        $this->tempDirectory = sys_get_temp_dir();
        $this->tempDirectory .= '/' . str_replace('.', '', microtime(true));
    }

    /**
     * Test exist.
     */
    public function testExist()
    {
        mkdir($this->tempDirectory);
        $this->assertTrue(Directory::exist($this->tempDirectory));
        if (is_dir($this->tempDirectory)) {
            rmdir($this->tempDirectory);
        }
    }

    /**
     * Test is writeable.
     */
    public function testIsWritable()
    {
        $this->assertFalse(Directory::isWritable($this->tempDirectory));
        mkdir($this->tempDirectory);
        $this->assertTrue(Directory::isWritable($this->tempDirectory));
        if (is_dir($this->tempDirectory)) {
            rmdir($this->tempDirectory);
        }
    }

    /**
     * Test make.
     */
    public function testMake()
    {
        $this->assertFalse(Directory::isWritable($this->tempDirectory));
        Directory::make($this->tempDirectory);
        $this->assertTrue(Directory::isWritable($this->tempDirectory));
        if (is_dir($this->tempDirectory)) {
            rmdir($this->tempDirectory);
        }
    }

    /**
     * Test entries.
     */
    public function testEntries()
    {
        $uniqueCode = md5(str_replace('.', '', microtime(true)));
        $filename1 = $uniqueCode . '1';
        $filename2 = $uniqueCode . '2';

        // Create entries on disk.
        Directory::make($this->tempDirectory . '/test');
        file_put_contents($this->tempDirectory . '/' . $filename1, 'test');
        file_put_contents($this->tempDirectory . '/test/' . $filename2, 'test');

        // Check entries.
        $entries = Directory::entries($this->tempDirectory, '*', true, true, true);
        $checkEntries = [
            $filename1,
            'test/' . $filename2,
            'test'
        ];
        $this->assertCount(3, $entries);
        foreach ($entries as $entry) {
            $this->assertTrue(in_array($entry['name'], $checkEntries));
        }

        // Clean up entries on disk.
        if (file_exists($this->tempDirectory . '/test/' . $filename2)) {
            unlink($this->tempDirectory . '/test/' . $filename2);
        }
        if (is_dir($this->tempDirectory . '/test')) {
            rmdir($this->tempDirectory . '/test');
        }
        if (file_exists($this->tempDirectory . '/' . $filename1)) {
            unlink($this->tempDirectory . '/' . $filename1);
        }
        if (is_dir($this->tempDirectory)) {
            rmdir($this->tempDirectory);
        }
    }
}
