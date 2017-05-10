<?php

use CoRex\Support\Config;
use CoRex\Support\System\Directory;
use CoRex\Support\System\File;
use CoRex\Support\System\Path;
use PHPUnit\Framework\TestCase;

/**
 * Class ConfigTest
 *
 * Warning: This test will create needed temp-directories/files in
 * sys sys_get_temp_dir() every time you run it.
 */
class ConfigTest extends TestCase
{
    private $tempDirectory;

    private $actor1 = ['firstname' => 'Sean', 'lastname' => 'Connery'];
    private $actor2 = ['firstname' => 'Roger', 'lastname' => 'Moore'];
    private $actor3 = ['firstname' => 'Timothy', 'lastname' => 'Dalton'];
    private $actor4 = ['firstname' => 'Pierce', 'lastname' => 'Brosnan'];
    private $actor5 = ['firstname' => 'Daniel', 'lastname' => 'Craig'];

    private $app1 = 'app.1';
    private $app2 = 'app.2';

    /**
     * Setup.
     */
    protected function setUp()
    {
        parent::setUp();
        Config::initialize(true);
        $this->tempDirectory = sys_get_temp_dir();
        $this->tempDirectory .= '/' . str_replace('.', '', microtime(true));
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
     * Test register app.
     */
    public function testRegisterApp()
    {
        $checkValue = microtime(true);

        // Register apps.
        Config::registerApp($checkValue, $this->app1);
        Config::registerApp($checkValue, $this->app2);

        // Compare app registrations.
        $apps = Config::getApps();
        $this->assertEquals(Path::root(['config']), $apps['*']);
        $this->assertEquals($checkValue, $apps[$this->app1]);
        $this->assertEquals($checkValue, $apps[$this->app2]);
    }

    /**
     * Test get object class exist.
     */
    public function testGetObjectClassExist()
    {
        $path = $this->getUniquePath();
        Config::registerApp($path);
        $this->prepareConfigFiles($path, 'test1', ['actor' => $this->actor1]);
        require_once(__DIR__ . '/Helpers/ConfigObjectHelper.php');
        $this->assertEquals(
            new ConfigObjectHelper(['actor' => $this->actor1]),
            Config::getObject('test1', ConfigObjectHelper::class)
        );
    }

    /**
     * Test get object class missing.
     */
    public function testGetObjectClassMissing()
    {
        $path = $this->getUniquePath();
        Config::registerApp($path);
        $this->prepareConfigFiles($path, 'test1', ['actor' => $this->actor1]);
        require_once(__DIR__ . '/Helpers/ConfigObjectHelper.php');
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Class test does not exist.');
        Config::getObject('test1', 'test');
    }

    /**
     * Test get closure.
     */
    public function testGetClosure()
    {
        $path = $this->getUniquePath();
        Config::registerApp($path);
        $this->prepareConfigFiles($path, 'test1', ['actor' => $this->actor1]);
        $data = Config::getClosure('test1', function ($data) {
            return $data;
        });
        $this->assertEquals(['actor' => $this->actor1], $data);
    }

    /**
     * Test get keys.
     */
    public function testGetKeys()
    {
        $path = $this->getUniquePath();
        Config::registerApp($path);
        $this->prepareConfigFiles($path, 'test', ['actor' => $this->actor1]);
        $this->assertEquals(['actor'], Config::getKeys('test'));
        $this->assertEquals(['firstname', 'lastname'], Config::getKeys('test.actor'));
    }

    /**
     * Test get.
     */
    public function testGet()
    {
        // Setup global.
        $path = $this->getUniquePath('1');
        Config::registerApp($path);
        $this->prepareConfigFiles($path, 'test1', ['actor' => $this->actor1]);

        // Setup app 1.
        $path = $this->getUniquePath('2');
        Config::registerApp($path, $this->app1);
        $this->prepareConfigFiles($path, 'test1', ['actor' => $this->actor2]);
        $this->prepareConfigFiles($path, 'test2', ['actor' => $this->actor3]);

        // Setup app 2.
        $path = $this->getUniquePath('3');
        Config::registerApp($path, $this->app2);
        $this->prepareConfigFiles($path, 'test1', ['actor' => $this->actor4]);
        $this->prepareConfigFiles($path, 'test2', ['actor' => $this->actor5]);

        // Test global
        $this->assertEquals($this->actor1['firstname'], Config::get('test1.actor.firstname'));
        $this->assertNull(Config::get('test1.actor.unknown'));

        // Test app 1.
        $this->assertEquals($this->actor2['firstname'], Config::get('test1.actor.firstname', null, $this->app1));
        $this->assertNull(Config::get('test1.actor.unknown', null, $this->app1));
        $this->assertEquals($this->actor3['firstname'], Config::get('test2.actor.firstname', null, $this->app1));
        $this->assertNull(Config::get('test1.actor.unknown', null, $this->app1));

        // Test app 2.
        $this->assertEquals($this->actor4['firstname'], Config::get('test1.actor.firstname', null, $this->app2));
        $this->assertNull(Config::get('test1.actor.unknown', null, $this->app2));
        $this->assertEquals($this->actor5['firstname'], Config::get('test2.actor.firstname', null, $this->app2));
        $this->assertNull(Config::get('test1.actor.unknown', null, $this->app2));
    }

    /**
     * Test get section.
     */
    public function testGetSection()
    {
        $path = $this->getUniquePath();
        Config::registerApp($path);
        $this->prepareConfigFiles($path, 'test1', ['actor' => $this->actor1]);
        $this->assertEquals($this->actor1, Config::get('test1.actor'));
    }

    /**
     * Test get apps.
     */
    public function testGetApps()
    {
        $this->testRegisterApp();
    }

    /**
     * Prepare config files.
     *
     * @param string $path
     * @param string $appName
     * @param array $data
     * @return string
     */
    private function prepareConfigFiles($path, $appName, array $data)
    {
        $filename = $path . '/' . $appName . '.php';
        $varExport = "<" . "?php\nreturn " . var_export($data, true) . ";\n";
        if (Directory::isWritable($path)) {
            File::put($filename, $varExport);
        }
        return $path;
    }

    /**
     * Get unique path.
     *
     * @param string $suffix
     * @return string
     */
    private function getUniquePath($suffix = '')
    {
        $microtime = microtime(true);
        $path = $this->tempDirectory . '/' . $microtime . $suffix;
        Directory::make($path);
        return $path;
    }
}
