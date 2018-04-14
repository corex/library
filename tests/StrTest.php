<?php

use CoRex\Support\Str;
use PHPUnit\Framework\TestCase;

class StrTest extends TestCase
{
    private $stringLeft = 'æøå';
    private $stringRight = 'ÆØÅ';
    private $template = '{left}{right}';
    private $part1 = 'part1';
    private $part2 = 'part2';
    private $slugTest = 'ThIs%is\a#certanly.test|with"funny-ChaRaCtErS/and^more$fun more+to_come';
    private $slugTestValid = 'thisisacertanly.testwithfunny.charactersandmorefun.moreto.come';
    private $pascalCase = 'TestClass';
    private $camelCase = 'testClass';
    private $snakeCase = 'test_class';
    private $kebabCase = 'test-class';
    private $idCamelCase = 'id';
    private $idPascalCase = 'Id';

    /**
     * Test length.
     */
    public function testLength()
    {
        $this->assertEquals(
            mb_strlen($this->stringLeft . $this->stringRight),
            Str::length($this->stringLeft . $this->stringRight)
        );
    }

    /**
     * Test lower.
     */
    public function testLower()
    {
        $this->assertEquals(
            $this->stringLeft . $this->stringLeft,
            Str::lower($this->stringLeft . $this->stringRight)
        );
    }

    /**
     * Test upper.
     */
    public function testUpper()
    {
        $this->assertEquals(
            $this->stringRight . $this->stringRight,
            Str::upper($this->stringLeft . $this->stringRight)
        );
    }

    /**
     * Test subtr.
     */
    public function testSubstr()
    {
        $this->assertEquals(
            $this->stringLeft,
            Str::substr($this->stringLeft . $this->stringRight, 0, 3)
        );
        $this->assertEquals(
            $this->stringRight,
            Str::substr($this->stringLeft . $this->stringRight, 3, 3)
        );
    }

    /**
     * Test left.
     */
    public function testLeft()
    {
        $this->assertEquals($this->stringLeft, Str::left($this->stringLeft . $this->stringRight, 3));
    }

    /**
     * Test right.
     */
    public function testRight()
    {
        $this->assertEquals($this->stringRight, Str::right($this->stringLeft . $this->stringRight, 3));
    }

    /**
     * Test starts with.
     */
    public function testStartsWith()
    {
        $this->assertTrue(Str::startsWith($this->stringLeft . $this->stringRight, $this->stringLeft));
    }

    /**
     * Test ends with.
     */
    public function testEndsWith()
    {
        $this->assertTrue(Str::endsWith($this->stringLeft . $this->stringRight, $this->stringRight));
    }

    /**
     * Test ucfirst.
     */
    public function testUcfirst()
    {
        $this->assertEquals(
            Str::substr($this->stringRight, 0, 1) . Str::substr($this->stringLeft, 1, 2),
            Str::ucfirst($this->stringLeft)
        );
    }

    /**
     * Test lcfirst.
     */
    public function testLcfirst()
    {
        $this->assertEquals(
            Str::substr($this->stringLeft, 0, 1) . Str::substr($this->stringRight, 1, 2),
            Str::lcfirst($this->stringRight)
        );
    }

    /**
     * Test limit.
     */
    public function testLimit()
    {
        $this->assertEquals(
            $this->stringLeft . Str::LIMIT_SUFFIX,
            Str::limit($this->stringLeft . $this->stringRight, 3)
        );
        $test = 'test';
        $this->assertEquals(
            $this->stringLeft . $test,
            Str::limit($this->stringLeft . $this->stringRight, 3, $test)
        );
        $this->assertEquals(
            'test',
            Str::limit('test', 10, $test)
        );
    }

    /**
     * Test is prefixed.
     */
    public function testIsPrefixed()
    {
        $this->assertFalse(Str::isPrefixed($this->stringLeft . $this->stringRight, $this->stringRight));
        $this->assertTrue(Str::isPrefixed($this->stringLeft . $this->stringRight, $this->stringLeft));
        $this->assertTrue(Str::isPrefixed('-test-', 't', '-'));
    }

    /**
     * Test strip prefix.
     */
    public function testStripPrefix()
    {
        $this->assertEquals(
            $this->stringRight,
            Str::stripPrefix($this->stringLeft . $this->stringRight, $this->stringLeft)
        );
        $this->assertEquals('', Str::stripPrefix('', '-'));
        $this->assertEquals('', Str::stripPrefix('--', '-', '-'));
    }

    /**
     * Test force prefix.
     */
    public function testForcePrefix()
    {
        $this->assertEquals(
            $this->stringLeft . $this->stringRight,
            Str::forcePrefix($this->stringRight, $this->stringLeft)
        );
        $this->assertEquals('', Str::forcePrefix('', '-'));
        $this->assertEquals('-_test', Str::forcePrefix('_test_', '-', '_'));
    }

    /**
     * Test is suffixed.
     */
    public function testIsSuffixed()
    {
        $this->assertFalse(Str::isSuffixed($this->stringLeft . $this->stringRight, $this->stringLeft));
        $this->assertTrue(Str::isSuffixed($this->stringLeft . $this->stringRight, $this->stringRight));
        $this->assertTrue(Str::isSuffixed('-test-', 't', '-'));
    }

    /**
     * Test strip suffix.
     */
    public function testStripSuffix()
    {
        $this->assertEquals(
            $this->stringLeft,
            Str::stripSuffix($this->stringLeft . $this->stringRight, $this->stringRight)
        );
        $this->assertEquals('', Str::stripSuffix('', '-'));
        $this->assertEquals('', Str::stripSuffix('--', '-', '-'));
    }

    /**
     * Test force suffix.
     */
    public function testForceSuffix()
    {
        $this->assertEquals(
            $this->stringLeft . $this->stringRight,
            Str::forceSuffix($this->stringLeft, $this->stringRight)
        );
        $this->assertEquals('', Str::forceSuffix('', '-'));
        $this->assertEquals('test_-', Str::forceSuffix('_test_', '-', '_'));
    }

    /**
     * Test replace token.
     */
    public function testReplaceToken()
    {
        $this->assertEquals($this->stringLeft . $this->stringRight, Str::replaceToken($this->template, [
            'left' => $this->stringLeft,
            'right' => $this->stringRight
        ]));
    }

    /**
     * Test remove first.
     */
    public function testRemoveFirst()
    {
        $this->assertEquals($this->part2, Str::removeFirst($this->part1 . '/' . $this->part2, '/'));
    }

    /**
     * Test remove last.
     */
    public function testRemoveLast()
    {
        $this->assertEquals($this->part1, Str::removeLast($this->part1 . '/' . $this->part2, '/'));
    }

    /**
     * Test get first.
     */
    public function testGetFirst()
    {
        $this->assertEquals($this->part1, Str::first($this->part1 . '/' . $this->part2, '/'));
    }

    /**
     * Test get last.
     */
    public function testGetLast()
    {
        $this->assertEquals($this->part2, Str::last($this->part1 . '/' . $this->part2, '/'));
    }

    /**
     * Test get part.
     */
    public function testGetPart()
    {
        $this->assertEquals($this->part1, Str::part($this->part1 . '/' . $this->part2, '/', 0));
        $this->assertEquals($this->part2, Str::part($this->part1 . '/' . $this->part2, '/', 1));
        $this->assertEquals('', Str::part($this->part1 . '/' . $this->part2, '/', 2));
    }

    /**
     * Test get csv fields.
     */
    public function testGetCsvFields()
    {
        $csv = '"' . $this->part1 . '","' . $this->part2 . '"';
        $this->assertEquals([$this->part1, $this->part2], Str::csvFields($csv));
        $this->assertEquals([], Str::csvFields(''));
        $csv = '\'' . $this->part1 . '\',\'' . $this->part2 . '\'';
        $this->assertEquals([$this->part1, $this->part2], Str::csvFields($csv));
    }

    /**
     * Test slug.
     */
    public function testSlug()
    {
        // Test standard separator.
        $this->assertEquals($this->slugTestValid, Str::slug($this->slugTest));

        // Test not standard separator.
        $this->assertEquals(
            str_replace('.', '-', $this->slugTestValid),
            Str::slug($this->slugTest, '-')
        );
    }

    /**
     * Test split into key value.
     */
    public function testSplitIntoKeyValue()
    {
        $uri = 'component/security/user/enable';
        $keys = ['type', 'component', 'controller', 'action'];
        $keyValues = Str::splitIntoKeyValue($uri, '/', $keys);
        $this->assertEquals(4, count($keyValues));
        $this->assertEquals('component', $keyValues['type']);
        $this->assertEquals('security', $keyValues['component']);
        $this->assertEquals('user', $keyValues['controller']);
        $this->assertEquals('enable', $keyValues['action']);
    }

    /**
     * Test split into key value.
     */
    public function testSplitIntoKeyValueLargerUri()
    {
        $uri = 'component/security/user/enable';
        $keys = ['type', 'component', 'controller'];
        $keyValues = Str::splitIntoKeyValue($uri, '/', $keys);
        $this->assertEquals(3, count($keyValues));
        $this->assertEquals('component', $keyValues['type']);
        $this->assertEquals('security', $keyValues['component']);
        $this->assertEquals('user', $keyValues['controller']);
    }

    /**
     * Test split into key value.
     */
    public function testSplitIntoKeyValueLargerKeys()
    {
        $uri = 'component/security/user';
        $keys = ['type', 'component', 'controller', 'action'];
        $keyValues = Str::splitIntoKeyValue($uri, '/', $keys);
        $this->assertEquals(3, count($keyValues));
        $this->assertEquals('component', $keyValues['type']);
        $this->assertEquals('security', $keyValues['component']);
        $this->assertEquals('user', $keyValues['controller']);
    }

    /**
     * Test unique standard.
     */
    public function testUniqueStandard()
    {
        $unique1 = Str::unique();
        $unique2 = Str::unique();
        $this->assertNotEquals($unique1, $unique2);
    }

    /**
     * Test unique prefix.
     */
    public function testUniquePrefix()
    {
        $uniquePrefix1 = Str::unique('test');
        $uniquePrefix2 = Str::unique('test');
        $this->assertNotEquals($uniquePrefix1, $uniquePrefix2);
        $this->assertEquals('test', substr($uniquePrefix1, 0, 4));
        $this->assertEquals('test', substr($uniquePrefix2, 0, 4));
        $this->assertNotEquals('test', substr($uniquePrefix1, -4));
        $this->assertNotEquals('test', substr($uniquePrefix2, -4));
    }

    /**
     * Test unique suffix.
     */
    public function testUniqueSuffix()
    {
        $uniqueSuffix1 = Str::unique('', 'test');
        $uniqueSuffix2 = Str::unique('', 'test');
        $this->assertNotEquals($uniqueSuffix1, $uniqueSuffix2);
        $this->assertNotEquals('test', substr($uniqueSuffix1, 0, 4));
        $this->assertNotEquals('test', substr($uniqueSuffix2, 0, 4));
        $this->assertEquals('test', substr($uniqueSuffix1, -4));
        $this->assertEquals('test', substr($uniqueSuffix2, -4));
    }

    /**
     * Test explode standard.
     */
    public function testExplodeStandard()
    {
        $string = "item1|item2|item3|item4";
        $stringResult = ['item1', 'item2', 'item3', 'item4'];
        $this->assertEquals($stringResult, Str::explode('|', $string));
    }

    /**
     * Test explode file string with cr.
     */
    public function testExplodeFileStringWithCR()
    {
        $string = "item1\r\nitem2\r\nitem3\r\nitem4";
        $stringResult = ['item1', 'item2', 'item3', 'item4'];
        $this->assertEquals($stringResult, Str::explode("\n", $string));
    }

    /**
     * Test explode with callable.
     */
    public function testExplodeWithCallable()
    {
        $string = "item1|item2|item3|item4";
        $stringResult = ['{item1}', '{item2}', '{item3}', '{item4}'];
        $this->assertEquals($stringResult, Str::explode('|', $string, function ($line) {
            return '{' . $line . '}';
        }));
    }

    /**
     * Test implode standard.
     */
    public function testImplodeStandard()
    {
        $items = ['item1', 'item2', 'item3', 'item4'];
        $itemsResult = "item1|item2|item3|item4";
        $this->assertEquals($itemsResult, Str::implode('|', $items));
    }

    /**
     * Test implode with callable.
     */
    public function testImplodeWithCallable()
    {
        $items = ['item1', 'item2', 'item3', 'item4'];
        $itemsResult = "{item1}|{item2}|{item3}|{item4}";
        $this->assertEquals($itemsResult, Str::implode('|', $items, function ($line) {
            return '{' . $line . '}';
        }));
    }

    /**
     * Test pad left default filler.
     */
    public function testPadLeftDefaultFiller()
    {
        $paddedString = Str::padLeft($this->stringLeft, 4);
        $this->assertEquals(' ' . $this->stringLeft, $paddedString);
    }

    /**
     * Test pad left specified filler.
     */
    public function testPadLeftSpecifiedFiller()
    {
        $paddedString = Str::padLeft($this->stringLeft, 4, '0');
        $this->assertEquals('0' . $this->stringLeft, $paddedString);
    }

    /**
     * Test pad right default filler.
     */
    public function testPadRightDefaultFiller()
    {
        $paddedString = Str::padRight($this->stringLeft, 4);
        $this->assertEquals($this->stringLeft . ' ', $paddedString);
    }

    /**
     * Test pad right specified filler.
     */
    public function testPadRightSpecifiedFIller()
    {
        $paddedString = Str::padRight($this->stringLeft, 4);
        $this->assertEquals($this->stringLeft . ' ', $paddedString);
    }

    /**
     * Test wrap not wrapped.
     */
    public function testWrapNotWrapped()
    {
        $string = 'test1 test2 test3';
        $wrapped = Str::wrap($string, 20);
        $this->assertEquals($string, $wrapped);
    }

    /**
     * Test wrap wrapped.
     */
    public function testWrapWrapped()
    {
        $string = 'test1 test2 test3';
        $wrapped = Str::wrap($string, 8);
        $this->assertEquals(str_replace(' ', "\n", $string), $wrapped);
    }

    /**
     * Test wrap empty.
     */
    public function testWrapEmpty()
    {
        $this->assertEquals('', Str::wrap('', 8));
    }

    /**
     * Test wrap empty with linebreak.
     */
    public function testWrapEmptyLinebreak()
    {
        $this->assertEquals("test\n", Str::wrap("test\n", 8));
    }

    /**
     * Test pascal case.
     */
    public function testPascalCase()
    {
        // Standard.
        $this->assertEquals($this->pascalCase, Str::pascalCase($this->pascalCase), 'CASE: pascal > pascal');
        $this->assertEquals($this->pascalCase, Str::pascalCase($this->camelCase), 'CASE: camel > pascal');
        $this->assertEquals($this->pascalCase, Str::pascalCase($this->snakeCase), 'CASE: snake > pascal');
        $this->assertEquals($this->pascalCase, Str::pascalCase($this->kebabCase), 'CASE: kebab > pascal');

        // Id.
        $this->assertEquals(
            $this->idPascalCase,
            Str::pascalCase($this->idCamelCase),
            'CASE: id camel > pascal'
        );
        $this->assertEquals(
            $this->idPascalCase,
            Str::pascalCase($this->idPascalCase),
            'CASE: id pascal > pascal'
        );
    }

    /**
     * Test camel case.
     */
    public function testCamelCase()
    {
        // Standard.
        $this->assertEquals($this->camelCase, Str::camelCase($this->pascalCase), 'CASE: pascal > camel');
        $this->assertEquals($this->camelCase, Str::camelCase($this->camelCase), 'CASE: camel > camel');
        $this->assertEquals($this->camelCase, Str::camelCase($this->snakeCase), 'CASE: snake > camel');
        $this->assertEquals($this->camelCase, Str::camelCase($this->kebabCase), 'CASE: kebab > camel');

        // Id.
        $this->assertEquals(
            $this->idCamelCase,
            Str::camelCase($this->idCamelCase),
            'CASE: id camel > camel'
        );
        $this->assertEquals(
            $this->idCamelCase,
            Str::camelCase($this->idPascalCase),
            'CASE: pascal > camel'
        );
    }

    /**
     * Test snake case.
     */
    public function testSnakeCase()
    {
        // Standard.
        $this->assertEquals($this->snakeCase, Str::snakeCase($this->pascalCase), 'CASE: pascal > snake');
        $this->assertEquals($this->snakeCase, Str::snakeCase($this->camelCase), 'CASE: camel > snake');
        $this->assertEquals($this->snakeCase, Str::snakeCase($this->snakeCase), 'CASE: snake > snake');
        $this->assertEquals($this->snakeCase, Str::snakeCase($this->kebabCase), 'CASE: kebab > snake');

        // Id.
        $this->assertEquals(
            $this->idCamelCase,
            Str::snakeCase($this->idCamelCase),
            'CASE: id camel > snake'
        );
        $this->assertEquals(
            $this->idCamelCase,
            Str::snakeCase($this->idPascalCase),
            'CASE: id pascal > snake'
        );
    }

    /**
     * Test kebab case.
     */
    public function testKebabCase()
    {
        // Standard.
        $this->assertEquals($this->kebabCase, Str::kebabCase($this->pascalCase), 'CASE: pascal > kebab');
        $this->assertEquals($this->kebabCase, Str::kebabCase($this->camelCase), 'CASE: camel > kebab');
        $this->assertEquals($this->kebabCase, Str::kebabCase($this->snakeCase), 'CASE: snake > kebab');
        $this->assertEquals($this->kebabCase, Str::kebabCase($this->kebabCase), 'CASE: kebab > kebab');

        // Id.
        $this->assertEquals(
            $this->idCamelCase,
            Str::kebabCase($this->idCamelCase),
            'CASE: id camel > kebab'
        );
        $this->assertEquals(
            $this->idCamelCase,
            Str::kebabCase($this->idPascalCase),
            'CASE: id pascal > kebab'
        );
    }

    /**
     * Test case convert array keys recursively.
     */
    public function testCaseConvertArrayKeysRecursively()
    {
        $convertedTest = Str::caseConvertArrayKeysRecursively([
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

    /**
     * Test strpos.
     */
    public function testStrpos()
    {
        // Check random character starting from 0+.
        $check = substr($this->slugTestValid, mt_rand(1, strlen($this->slugTestValid) - 1), 1);
        $checkPos = strpos($this->slugTestValid, $check);
        $pos = Str::strpos($this->slugTestValid, $check);
        $this->assertTrue(is_int($pos));
        $this->assertEquals($checkPos, $pos);

        // Check first character.
        $check = substr($this->slugTestValid, 0, 1);
        $pos = Str::strpos($this->slugTestValid, $check);
        $this->assertTrue(is_int($pos));
        $this->assertEquals(0, $pos);

        // Check unknown character.
        $check = '-';
        $pos = Str::strpos($this->slugTestValid, $check);
        $this->assertFalse(is_int($pos));
        $this->assertFalse($pos);
    }

    /**
     * Test indexOf.
     */
    public function testIndexOf()
    {
        // Check random character starting from 0+.
        $check = substr($this->slugTestValid, mt_rand(1, strlen($this->slugTestValid) - 1), 1);
        $checkPos = strpos($this->slugTestValid, $check);
        $pos = Str::indexOf($this->slugTestValid, $check);
        $this->assertTrue(is_int($pos));
        $this->assertEquals($checkPos, $pos);

        // Check first character.
        $check = substr($this->slugTestValid, 0, 1);
        $pos = Str::indexOf($this->slugTestValid, $check);
        $this->assertTrue(is_int($pos));
        $this->assertEquals(0, $pos);

        // Check unknown character.
        $check = '-';
        $pos = Str::indexOf($this->slugTestValid, $check);
        $this->assertTrue(is_int($pos));
        $this->assertEquals(-1, $pos);
    }

    /**
     * Test contains.
     */
    public function testContains()
    {
        // Check random character starting from 0+.
        $check = substr($this->slugTestValid, mt_rand(1, strlen($this->slugTestValid) - 1), 1);
        $this->assertTrue(Str::contains($this->slugTestValid, $check));

        // Check first character.
        $check = substr($this->slugTestValid, 0, 1);
        $this->assertTrue(Str::contains($this->slugTestValid, $check));

        // Check unknown character.
        $check = '-';
        $this->assertFalse(Str::contains($this->slugTestValid, $check));
    }
}
