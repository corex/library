<?php

use CoRex\Support\System\Directory;
use CoRex\Support\System\File;
use CoRex\Support\System\Template;
use PHPUnit\Framework\TestCase;

class TemplateTest extends TestCase
{
    private $tempDirectory;
    private $tempFilename;

    /**
     * Setup.
     */
    protected function setUp()
    {
        parent::setUp();
        $this->tempDirectory = sys_get_temp_dir();
        $this->tempDirectory .= '/' . str_replace('.', '', microtime(true));
        Directory::make($this->tempDirectory);
        $this->tempFilename = File::getTempFilename($this->tempDirectory);
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
     * Test constructor.
     *
     * @throws ReflectionException
     */
    public function testConstructor()
    {
        $template = new Template($this->tempDirectory);
        $properties = $this->getPrivatePropertiesFromStaticClass(Template::class, $template);
        $this->assertEquals($this->tempDirectory, $properties['path']);
        $this->assertTrue(is_string($properties['template']));
        $this->assertEquals('', ($properties['template']));
        $this->assertTrue(is_string($properties['content']));
        $this->assertEquals('', ($properties['content']));
        $this->assertTrue(is_array($properties['tokens']));
        $this->assertEquals([], ($properties['tokens']));
    }

    /**
     * Test clear.
     *
     * @throws ReflectionException
     */
    public function testClear()
    {
        $template = new Template($this->tempDirectory);
        $template->setTemplate('test');
        $template->setToken('test', 'test');
        $properties = $this->getPrivatePropertiesFromStaticClass(Template::class, $template);

        // Check not cleared.
        $this->assertTrue(is_string($properties['template']));
        $this->assertEquals('', ($properties['template']));
        $this->assertTrue(is_string($properties['content']));
        $this->assertNotEquals('', ($properties['content']));
        $this->assertTrue(is_array($properties['tokens']));
        $this->assertNotEquals([], ($properties['tokens']));

        $template->clear();

        // Check cleared.
        $properties = $this->getPrivatePropertiesFromStaticClass(Template::class, $template);
        $this->assertTrue(is_string($properties['template']));
        $this->assertEquals('', ($properties['template']));
        $this->assertTrue(is_string($properties['content']));
        $this->assertEquals('', ($properties['content']));
        $this->assertTrue(is_array($properties['tokens']));
        $this->assertEquals([], ($properties['tokens']));
    }

    /**
     * Test load template.
     */
    public function testLoadTemplate()
    {
        $content = '{{token1}}|{{token2}}';
        file_put_contents($this->tempFilename . '.tpl', $content);
        $template = new Template($this->tempDirectory);
        $this->assertTrue($template->loadTemplate(basename($this->tempFilename)));
        $this->assertEquals('|', $template->render());
    }

    /**
     * Test set template.
     */
    public function testSetTemplate()
    {
        $content = '{{token1}}|{{token2}}';
        $template = new Template($this->tempDirectory);
        $template->setTemplate($content);
        $this->assertEquals('|', $template->render());
    }

    /**
     * Test render.
     */
    public function testRender()
    {
        $content = '{{token1}}|{{token2}}';
        $template = new Template($this->tempDirectory);
        $template->setTemplate($content);
        $template->setToken('token1', 'test1');
        $template->setToken('token2', 'test2');
        $this->assertEquals('test1|test2', $template->render());
    }

    /**
     * Test set token.
     */
    public function testSetToken()
    {
        $this->testRender();
    }

    /**
     * Test set tokens.
     */
    public function testSetTokens()
    {
        $content = '{{token1}}|{{token2}}';
        $template = new Template($this->tempDirectory);
        $template->setTemplate($content);
        $template->setTokens([
            'token1' => 'test1',
            'token2' => 'test2'
        ]);
        $this->assertEquals('test1|test2', $template->render());
    }

    /**
     * Test set token values no separator.
     */
    public function testSetTokenValuesNoSeparator()
    {
        $content = '{{token1}}|{{token2}}';
        $template = new Template($this->tempDirectory);
        $template->setTemplate($content);
        $template->setTokenValues('token1', ['test1', 'test2']);
        $template->setTokenValues('token2', ['test3', 'test4']);
        $this->assertEquals('test1test2|test3test4', $template->render());
    }

    /**
     * Test set token values separator.
     */
    public function testSetTokenValuesSeparator()
    {
        $content = '{{token1}}|{{token2}}';
        $template = new Template($this->tempDirectory);
        $template->setTemplate($content);
        $template->setTokenValues('token1', ['test1', 'test2'], '|');
        $template->setTokenValues('token2', ['test3', 'test4'], '|');
        $this->assertEquals('test1|test2|test3|test4', $template->render());
    }

    /**
     * Test set token values prefix suffix.
     */
    public function testSetTokenValuesPrefixSuffix()
    {
        $content = '{{token1}}|{{token2}}';
        $template = new Template($this->tempDirectory);
        $template->setTemplate($content);
        $template->setTokenValues('token1', ['test1', 'test2'], '', '[', ']');
        $template->setTokenValues('token2', ['test3', 'test4'], '', '[', ']');
        $this->assertEquals('[test1][test2]|[test3][test4]', $template->render());
    }

    /**
     * Get private properties from static class.
     *
     * @param string $className
     * @param object $object Default null which means new $className().
     * @return array
     * @throws ReflectionException
     */
    private function getPrivatePropertiesFromStaticClass($className, $object = null)
    {
        $result = [];
        if ($object === null) {
            $object = new $className();
        }
        $reflectionClass = new ReflectionClass($className);
        $properties = $reflectionClass->getProperties(ReflectionProperty::IS_PRIVATE);
        if (count($properties) > 0) {
            foreach ($properties as $property) {
                $property->setAccessible(true);
                $result[$property->getName()] = $property->getValue($object);
            }
        }
        return $result;
    }
}