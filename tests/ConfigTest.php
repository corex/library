<?php

use CoRex\Support\Config;
use CoRex\Support\Obj;
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
        Obj::callMethod('initialize', null, [true, false], Config::class);
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
     * Test clear.
     */
    public function testClear()
    {
        Config::clear();
        $this->assertEquals([], Config::getData(false));
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
     *
     * @throws Exception
     */
    public function testGetObjectClassExist()
    {
        $path = $this->getUniquePath();
        $this->prepareConfigFiles($path, 'test1', ['actor' => $this->actor1]);
        Config::registerApp($path);
        require_once(__DIR__ . '/Helpers/ConfigObjectHelper.php');
        $this->assertEquals(
            new ConfigObjectHelper(['actor' => $this->actor1]),
            Config::getObject('test1', ConfigObjectHelper::class)
        );
    }

    /**
     * Test get object class missing.
     *
     * @throws Exception
     */
    public function testGetObjectClassMissing()
    {
        $path = $this->getUniquePath();
        $this->prepareConfigFiles($path, 'test1', ['actor' => $this->actor1]);
        Config::registerApp($path);
        require_once(__DIR__ . '/Helpers/ConfigObjectHelper.php');
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Class test does not exist.');
        Config::getObject('test1', 'test');
    }

    /**
     * Test get closure.
     *
     * @throws Exception
     */
    public function testGetClosure()
    {
        $path = $this->getUniquePath();
        $this->prepareConfigFiles($path, 'test1', ['actor' => $this->actor1]);
        Config::registerApp($path);
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
        $this->prepareConfigFiles($path, 'test', ['actor' => $this->actor1]);
        Config::registerApp($path);
        $this->assertEquals(['actor'], Config::getKeys('test'));
        $this->assertEquals(['firstname', 'lastname'], Config::getKeys('test.actor'));
    }

    /**
     * Test get.
     *
     * @throws Exception
     */
    public function testGet()
    {
        // Setup global.
        $path = $this->getUniquePath('1');
        $this->prepareConfigFiles($path, 'test1', ['actor' => $this->actor1]);
        Config::registerApp($path);

        // Setup app 1.
        $path = $this->getUniquePath('2');
        $this->prepareConfigFiles($path, 'test1', ['actor' => $this->actor2]);
        $this->prepareConfigFiles($path, 'test2', ['actor' => $this->actor3]);
        Config::registerApp($path, $this->app1);

        // Setup app 2.
        $path = $this->getUniquePath('3');
        $this->prepareConfigFiles($path, 'test1', ['actor' => $this->actor4]);
        $this->prepareConfigFiles($path, 'test2', ['actor' => $this->actor5]);
        Config::registerApp($path, $this->app2);

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
     * Test get multiple.
     *
     * @throws Exception
     */
    public function testGetMultiple()
    {
        $path = $this->getUniquePath();
        $pathRoot = $path;

        // Create data level 1.
        $this->prepareConfigFiles($path, 'level2', ['actor' => $this->actor1]);

        // Create data level 2.
        $path .= '/level2';
        Directory::make($path);
        $this->prepareConfigFiles($path, 'level3', ['actor' => $this->actor2]);

        // Create data level 3.
        $path .= '/level3';
        Directory::make($path);
        $this->prepareConfigFiles($path, 'level4', ['actor' => $this->actor3]);

        // Create data level 4.
        $path .= '/level4';
        Directory::make($path);
        $this->prepareConfigFiles($path, 'actor', ['actor' => $this->actor4]);

        Config::registerApp($pathRoot);

        $this->assertEquals($this->actor1, Config::get('level2.actor'));

        $this->assertEquals($this->actor2, Config::get('level2.level3.actor'));

        $check = $this->actor3;
        $check['actor'] = $this->actor4;
        $this->assertEquals($check, Config::get('level2.level3.level4.actor'));

        $this->assertEquals($this->actor4, Config::get('level2.level3.level4.actor.actor'));
    }

    /**
     * Test get section.
     *
     * @throws Exception
     */
    public function testGetSection()
    {
        $path = $this->getUniquePath();
        $this->prepareConfigFiles($path, 'test1', ['actor' => $this->actor1]);
        Config::registerApp($path);
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
     * Test set.
     *
     * @throws Exception
     */
    public function testSet()
    {
        Config::clear();
        $path = md5(mt_rand(1, 100000));
        $check = md5(mt_rand(1, 100000));
        Config::set($path, $check);
        $this->assertEquals($check, Config::get($path));
    }

    /**
     * Test set file path string empty.
     *
     * @throws Exception
     */
    public function testSetFilePathStringEmpty()
    {
        Config::clear();
        $path = $this->getUniquePath();
        $this->prepareConfigFiles($path, 'test', ['actor' => $this->actor1]);
        Config::setFile('', $path . '/test');
        $this->assertEquals($this->actor1, Config::get('actor'));
    }

    /**
     * Test set file path null.
     *
     * @throws Exception
     */
    public function testSetFilePathNull()
    {
        Config::clear();
        $path = $this->getUniquePath();
        $this->prepareConfigFiles($path, 'test', ['actor' => $this->actor1]);
        Config::setFile(null, $path . '/test');
        $this->assertEquals($this->actor1, Config::get('actor'));
    }

    /**
     * Test set file path valid.
     *
     * @throws Exception
     */
    public function testSetFilePathValid()
    {
        $check = md5(mt_rand(1, 100000));
        Config::clear();
        $path = $this->getUniquePath();
        $this->prepareConfigFiles($path, 'test', ['actor' => $this->actor1]);
        Config::setFile($check, $path . '/test');
        $this->assertEquals($this->actor1, Config::get($check . '.actor'));
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
