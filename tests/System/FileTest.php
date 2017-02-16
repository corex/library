<?php

use CoRex\Support\Str;
use CoRex\Support\System\File;

class FileTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test exist.
     */
    public function testExist()
    {
        $filename = $this->getTempFilename();
        touch($filename);
        $this->assertTrue(File::exist($filename));
        File::delete($filename);
        $this->assertFalse(File::exist($filename));
    }

    /**
     * Test load.
     */
    public function testLoad()
    {
        $test = 'test';
        $filename = $this->getTempFilename();
        File::save($filename, $test);
        $this->assertEquals($test, File::load($filename));
        File::delete($filename);
    }

    /**
     * Test load lines.
     */
    public function testLoadLines()
    {
        $lines = ['test1', 'test2'];

        // Test load with "\n".
        $filename = $this->getTempFilename();
        File::save($filename, implode("\n", $lines));
        $this->assertEquals($lines, File::loadLines($filename));
        File::delete($filename);

        // Test load with "\r\n".
        $filename = $this->getTempFilename();
        File::save($filename, implode("\r\n", $lines));
        $this->assertEquals($lines, File::loadLines($filename));
        File::delete($filename);
    }

    /**
     * Test save.
     */
    public function testSave()
    {
        $this->testLoad();
    }

    /**
     * Test save lines.
     */
    public function testSaveLines()
    {
        $lines = ['test1', 'test2'];
        $filename = $this->getTempFilename();
        File::saveLines($filename, $lines);
        $this->assertEquals($lines, File::loadLines($filename));
        File::delete($filename);
    }

    /**
     * Test get stub.
     */
    public function testGetStub()
    {
        $stub = '({firstname}/{lastname})';
        $result = str_replace('{firstname}', 'test1', $stub);
        $result = str_replace('{lastname}', 'test2', $result);
        $filename = $this->getTempFilename('stub');
        File::save($filename, $stub);
        $this->assertEquals($result, File::getStub($filename, [
            'firstname' => 'test1',
            'lastname' => 'test2'
        ]));
        File::delete($filename);
    }

    /**
     * Test get template.
     */
    public function testGetTemplate()
    {
        $template = '({firstname}/{lastname})';
        $result = str_replace('{firstname}', 'test1', $template);
        $result = str_replace('{lastname}', 'test2', $result);
        $filename = $this->getTempFilename('tpl');
        File::save($filename, $template);
        $this->assertEquals($result, File::getTemplate($filename, [
            'firstname' => 'test1',
            'lastname' => 'test2'
        ]));
        File::delete($filename);
    }

    /**
     * Test load json.
     */
    public function testLoadJson()
    {
        $lines = ['firstname' => 'test1', 'lastname' => 'test2'];
        $filename = $this->getTempFilename('json');
        File::saveJson($filename, $lines);
        $this->assertEquals($lines, File::loadJson($filename));
        File::delete($filename);
    }

    /**
     * Test save json.
     */
    public function testSaveJson()
    {
        $this->testLoadJson();
    }

    /**
     * Test get temp filename.
     */
    public function testGetTempFilename()
    {
        $this->assertNotEquals($this->getTempFilename(), $this->getTempFilename());
    }

    /**
     * Test delete.
     */
    public function testDelete()
    {
        $filename = $this->getTempFilename();
        touch($filename);
        $this->assertTrue(file_exists($filename));
        File::delete($filename);
        $this->assertFalse(file_exists($filename));
    }

    /**
     * Get temp filename.
     *
     * @param string $extension Default ''.
     * @return string
     */
    private function getTempFilename($extension = '')
    {
        $filename = tempnam(sys_get_temp_dir(), '');
        if ($extension != '' && !Str::endsWith($filename, '.' . $extension)) {
            $filename .= '.' . $extension;
        }
        return $filename;
    }
}
