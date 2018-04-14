<?php

use CoRex\Support\System\Session;
use CoRex\Support\System\Token;
use PHPUnit\Framework\TestCase;

class TokenTest extends TestCase
{
    private $test1 = 'test 1';
    private $test2 = 'test 2';

    /**
     * Setup.
     */
    protected function setUp()
    {
        parent::setUp();
        Token::clear();
    }

    /**
     * Test create.
     */
    public function testCreate()
    {
        $token1 = Token::create($this->test1);
        $token2 = Token::create($this->test2);
        $this->assertGreaterThan(30, strlen($token1));
        $this->assertGreaterThan(30, strlen($token2));
        $this->assertNotEquals($token1, $token2);
    }

    /**
     * Test get.
     */
    public function testGet()
    {
        // Test if tokens exists.
        $this->assertFalse(Token::has($this->test1));
        $this->assertFalse(Token::has($this->test2));

        // Create tokens.
        $token1 = Token::create($this->test1);
        $token2 = Token::create($this->test2);

        // Check tokens.
        $this->assertEquals($token1, Token::get($this->test1));
        $this->assertEquals($token2, Token::get($this->test2));

        $this->assertNull(Token::get('unknown'));
    }

    /**
     * Test has.
     */
    public function testHas()
    {
        // Check if tokens exists.
        $this->assertFalse(Token::has($this->test1));
        $this->assertFalse(Token::has($this->test2));

        // Create tokens.
        Token::create($this->test1);
        Token::create($this->test2);

        // Check if tokens exists.
        $this->assertTrue(Token::has($this->test1));
        $this->assertTrue(Token::has($this->test2));
    }

    /**
     * Test is valid.
     */
    public function testIsValid()
    {
        // Get and test standard token.
        $token1 = Token::create($this->test1);

        // Set and test older token.
        Token::create($this->test2);
        $tokenData = Session::get($this->test2, null, Token::$namespace);
        $tokenData['time'] -= 500;
        Session::set($this->test2, $tokenData, Token::$namespace);
        $token2 = Token::get($this->test2);

        // Validate tokens.
        $this->assertTrue(Token::isValid($this->test1, $token1));
        $this->assertFalse(Token::isValid($this->test2, $token2));

        $this->assertFalse(Token::isValid('unknown', 'unknown'));
    }

    /**
     * Test delete.
     */
    public function testDelete()
    {
        // Test if tokens exists.
        $this->assertFalse(Token::has($this->test1));
        $this->assertFalse(Token::has($this->test2));

        // Create test tokens.
        Token::create($this->test1);
        Token::create($this->test2);

        // Test if tokens exists.
        $this->assertTrue(Token::has($this->test1));
        $this->assertTrue(Token::has($this->test2));

        // Delete tokens.
        Token::delete($this->test1);
        Token::delete($this->test2);

        // Test if tokens exists.
        $this->assertFalse(Token::has($this->test1));
        $this->assertFalse(Token::has($this->test2));
    }
}