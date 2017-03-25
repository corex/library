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
    }

    /**
     * Test is prefixed.
     */
    public function testIsPrefixed()
    {
        $this->assertFalse(Str::isPrefixed($this->stringLeft . $this->stringRight, $this->stringRight));
        $this->assertTrue(Str::isPrefixed($this->stringLeft . $this->stringRight, $this->stringLeft));
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
    }

    /**
     * Test is suffixed.
     */
    public function testIsSuffixed()
    {
        $this->assertFalse(Str::isSuffixed($this->stringLeft . $this->stringRight, $this->stringLeft));
        $this->assertTrue(Str::isSuffixed($this->stringLeft . $this->stringRight, $this->stringRight));
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
     * Test remove last.
     */
    public function testRemoveLast()
    {
        $this->assertEquals($this->part1, Str::removeLast($this->part1 . '/' . $this->part2, '/'));
    }

    /**
     * Test get last.
     */
    public function testGetLast()
    {
        $this->assertEquals($this->part2, Str::getLast($this->part1 . '/' . $this->part2, '/'));
    }

    /**
     * Test get part.
     */
    public function testGetPart()
    {
        $this->assertEquals($this->part1, Str::getPart($this->part1 . '/' . $this->part2, '/', 1));
        $this->assertEquals($this->part2, Str::getPart($this->part1 . '/' . $this->part2, '/', 2));
        $this->assertEquals('', Str::getPart($this->part1 . '/' . $this->part2, '/', 3));
    }

    /**
     * Test get csv fields.
     */
    public function testGetCsvFields()
    {
        $csv = '"' . $this->part1 . '","' . $this->part2 . '"';
        $this->assertEquals([$this->part1, $this->part2], Str::getCsvFields($csv));
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
}
