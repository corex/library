<?php

use CoRex\Support\Code\Convention;

class ConventionTest extends PHPUnit_Framework_TestCase
{
    private $studlyCase = 'TestClass';
    private $pascalCase = 'TestClass';
    private $camelCase = 'testClass';
    private $snakeCase = 'test_class';
    private $kebabCase = 'test-class';
    private $idCamelCase = 'id';
    private $idStudlyCase = 'Id';

    /**
     * Test studly.
     */
    public function testStudly()
    {
        // Standard.
        $this->assertEquals($this->studlyCase, Convention::studly($this->studlyCase));
        $this->assertEquals($this->studlyCase, Convention::studly($this->pascalCase));
        $this->assertEquals($this->studlyCase, Convention::studly($this->camelCase));
        $this->assertEquals($this->studlyCase, Convention::studly($this->snakeCase));
        $this->assertEquals($this->studlyCase, Convention::studly($this->kebabCase));

        // Id.
        $this->assertEquals($this->idStudlyCase, Convention::studly($this->idCamelCase));
        $this->assertEquals($this->idStudlyCase, Convention::studly($this->idStudlyCase));
    }

    /**
     * Test Pascal.
     */
    public function testPascal()
    {
        // Standard.
        $this->assertEquals($this->pascalCase, Convention::pascal($this->studlyCase));
        $this->assertEquals($this->pascalCase, Convention::pascal($this->pascalCase));
        $this->assertEquals($this->pascalCase, Convention::pascal($this->camelCase));
        $this->assertEquals($this->pascalCase, Convention::pascal($this->snakeCase));
        $this->assertEquals($this->pascalCase, Convention::pascal($this->kebabCase));

        // Id.
        $this->assertEquals($this->idStudlyCase, Convention::pascal($this->idCamelCase));
        $this->assertEquals($this->idStudlyCase, Convention::pascal($this->idStudlyCase));
    }

    /**
     * Test camel.
     */
    public function testGetCamel()
    {
        // Standard.
        $this->assertEquals($this->camelCase, Convention::camel($this->studlyCase));
        $this->assertEquals($this->camelCase, Convention::camel($this->pascalCase));
        $this->assertEquals($this->camelCase, Convention::camel($this->camelCase));
        $this->assertEquals($this->camelCase, Convention::camel($this->snakeCase));
        $this->assertEquals($this->camelCase, Convention::camel($this->kebabCase));

        // Id.
        $this->assertEquals($this->idCamelCase, Convention::camel($this->idCamelCase));
        $this->assertEquals($this->idCamelCase, Convention::camel($this->idStudlyCase));
    }

    /**
     * Test get snake_case.
     */
    public function testGetSnake()
    {
        // Standard.
        $this->assertEquals($this->snakeCase, Convention::snake($this->studlyCase));
        $this->assertEquals($this->snakeCase, Convention::snake($this->pascalCase));
        $this->assertEquals($this->snakeCase, Convention::snake($this->camelCase));
        $this->assertEquals($this->snakeCase, Convention::snake($this->snakeCase));
        $this->assertEquals($this->snakeCase, Convention::snake($this->kebabCase));

        // Id.
        $this->assertEquals($this->idCamelCase, Convention::snake($this->idCamelCase));
        $this->assertEquals($this->idCamelCase, Convention::snake($this->idStudlyCase));
    }

    /**
     * Test get kebab case.
     */
    public function testGetKebab()
    {
        // Standard.
        $this->assertEquals($this->kebabCase, Convention::kebab($this->studlyCase));
        $this->assertEquals($this->kebabCase, Convention::kebab($this->pascalCase));
        $this->assertEquals($this->kebabCase, Convention::kebab($this->camelCase));
        $this->assertEquals($this->kebabCase, Convention::kebab($this->snakeCase));
        $this->assertEquals($this->kebabCase, Convention::kebab($this->kebabCase));

        // Id.
        $this->assertEquals($this->idCamelCase, Convention::kebab($this->idCamelCase));
        $this->assertEquals($this->idCamelCase, Convention::kebab($this->idStudlyCase));
    }

    /**
     * Test convert array keys recursively.
     */
    public function testConvertArrayKeysRecursively()
    {
        $convertedTest = Convention::convertArrayKeysRecursively([
            'id' => [
                'firstname' => 'Roger',
                'lastname' => 'Moore'
            ]
        ]);
        $this->assertEquals([
            'Id' => [
                'Firstname' => 'Roger',
                'Lastname' => 'Moore'
            ]
        ], $convertedTest);
    }
}
