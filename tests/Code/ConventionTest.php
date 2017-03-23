<?php

use CoRex\Support\Code\Convention;
use PHPUnit\Framework\TestCase;

class ConventionTest extends TestCase
{
    private $pascalCase = 'TestClass';
    private $camelCase = 'testClass';
    private $snakeCase = 'test_class';
    private $kebabCase = 'test-class';
    private $idCamelCase = 'id';
    private $idPascalCase = 'Id';

    /**
     * Test pascal.
     */
    public function testPascal()
    {
        // Standard.
        $this->assertEquals($this->pascalCase, Convention::pascal($this->pascalCase), 'CASE: pascal > pascal');
        $this->assertEquals($this->pascalCase, Convention::pascal($this->camelCase), 'CASE: camel > pascal');
        $this->assertEquals($this->pascalCase, Convention::pascal($this->snakeCase), 'CASE: snake > pascal');
        $this->assertEquals($this->pascalCase, Convention::pascal($this->kebabCase), 'CASE: kebab > pascal');

        // Id.
        $this->assertEquals($this->idPascalCase, Convention::pascal($this->idCamelCase), 'CASE: id camel > pascal');
        $this->assertEquals($this->idPascalCase, Convention::pascal($this->idPascalCase), 'CASE: id pascal > pascal');
    }

    /**
     * Test camel.
     */
    public function testGetCamel()
    {
        // Standard.
        $this->assertEquals($this->camelCase, Convention::camel($this->pascalCase), 'CASE: pascal > camel');
        $this->assertEquals($this->camelCase, Convention::camel($this->camelCase), 'CASE: camel > camel');
        $this->assertEquals($this->camelCase, Convention::camel($this->snakeCase), 'CASE: snake > camel');
        $this->assertEquals($this->camelCase, Convention::camel($this->kebabCase), 'CASE: kebab > camel');

        // Id.
        $this->assertEquals($this->idCamelCase, Convention::camel($this->idCamelCase), 'CASE: id camel > camel');
        $this->assertEquals($this->idCamelCase, Convention::camel($this->idPascalCase), 'CASE: pascal > camel');
    }

    /**
     * Test get snake.
     */
    public function testGetSnake()
    {
        // Standard.
        $this->assertEquals($this->snakeCase, Convention::snake($this->pascalCase), 'CASE: pascal > snake');
        $this->assertEquals($this->snakeCase, Convention::snake($this->camelCase), 'CASE: camel > snake');
        $this->assertEquals($this->snakeCase, Convention::snake($this->snakeCase), 'CASE: snake > snake');
        $this->assertEquals($this->snakeCase, Convention::snake($this->kebabCase), 'CASE: kebab > snake');

        // Id.
        $this->assertEquals($this->idCamelCase, Convention::snake($this->idCamelCase), 'CASE: id camel > snake');
        $this->assertEquals($this->idCamelCase, Convention::snake($this->idPascalCase), 'CASE: id pascal > snake');
    }

    /**
     * Test get kebab.
     */
    public function testGetKebab()
    {
        // Standard.
        $this->assertEquals($this->kebabCase, Convention::kebab($this->pascalCase), 'CASE: pascal > kebab');
        $this->assertEquals($this->kebabCase, Convention::kebab($this->camelCase), 'CASE: camel > kebab');
        $this->assertEquals($this->kebabCase, Convention::kebab($this->snakeCase), 'CASE: snake > kebab');
        $this->assertEquals($this->kebabCase, Convention::kebab($this->kebabCase), 'CASE: kebab > kebab');

        // Id.
        $this->assertEquals($this->idCamelCase, Convention::kebab($this->idCamelCase), 'CASE: id camel > kebab');
        $this->assertEquals($this->idCamelCase, Convention::kebab($this->idPascalCase), 'CASE: id pascal > kebab');
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
