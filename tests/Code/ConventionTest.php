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
        $this->assertEquals($this->pascalCase, Convention::pascalCase($this->pascalCase), 'CASE: pascal > pascal');
        $this->assertEquals($this->pascalCase, Convention::pascalCase($this->camelCase), 'CASE: camel > pascal');
        $this->assertEquals($this->pascalCase, Convention::pascalCase($this->snakeCase), 'CASE: snake > pascal');
        $this->assertEquals($this->pascalCase, Convention::pascalCase($this->kebabCase), 'CASE: kebab > pascal');

        // Id.
        $this->assertEquals(
            $this->idPascalCase,
            Convention::pascalCase($this->idCamelCase),
            'CASE: id camel > pascal'
        );
        $this->assertEquals(
            $this->idPascalCase,
            Convention::pascalCase($this->idPascalCase),
            'CASE: id pascal > pascal'
        );
    }

    /**
     * Test camel.
     */
    public function testGetCamel()
    {
        // Standard.
        $this->assertEquals($this->camelCase, Convention::camelCase($this->pascalCase), 'CASE: pascal > camel');
        $this->assertEquals($this->camelCase, Convention::camelCase($this->camelCase), 'CASE: camel > camel');
        $this->assertEquals($this->camelCase, Convention::camelCase($this->snakeCase), 'CASE: snake > camel');
        $this->assertEquals($this->camelCase, Convention::camelCase($this->kebabCase), 'CASE: kebab > camel');

        // Id.
        $this->assertEquals(
            $this->idCamelCase,
            Convention::camelCase($this->idCamelCase),
            'CASE: id camel > camel'
        );
        $this->assertEquals(
            $this->idCamelCase,
            Convention::camelCase($this->idPascalCase),
            'CASE: pascal > camel'
        );
    }

    /**
     * Test get snake.
     */
    public function testGetSnake()
    {
        // Standard.
        $this->assertEquals($this->snakeCase, Convention::snakeCase($this->pascalCase), 'CASE: pascal > snake');
        $this->assertEquals($this->snakeCase, Convention::snakeCase($this->camelCase), 'CASE: camel > snake');
        $this->assertEquals($this->snakeCase, Convention::snakeCase($this->snakeCase), 'CASE: snake > snake');
        $this->assertEquals($this->snakeCase, Convention::snakeCase($this->kebabCase), 'CASE: kebab > snake');

        // Id.
        $this->assertEquals(
            $this->idCamelCase,
            Convention::snakeCase($this->idCamelCase),
            'CASE: id camel > snake'
        );
        $this->assertEquals(
            $this->idCamelCase,
            Convention::snakeCase($this->idPascalCase),
            'CASE: id pascal > snake'
        );
    }

    /**
     * Test get kebab.
     */
    public function testGetKebab()
    {
        // Standard.
        $this->assertEquals($this->kebabCase, Convention::kebabCase($this->pascalCase), 'CASE: pascal > kebab');
        $this->assertEquals($this->kebabCase, Convention::kebabCase($this->camelCase), 'CASE: camel > kebab');
        $this->assertEquals($this->kebabCase, Convention::kebabCase($this->snakeCase), 'CASE: snake > kebab');
        $this->assertEquals($this->kebabCase, Convention::kebabCase($this->kebabCase), 'CASE: kebab > kebab');

        // Id.
        $this->assertEquals(
            $this->idCamelCase,
            Convention::kebabCase($this->idCamelCase),
            'CASE: id camel > kebab'
        );
        $this->assertEquals(
            $this->idCamelCase,
            Convention::kebabCase($this->idPascalCase),
            'CASE: id pascal > kebab'
        );
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
