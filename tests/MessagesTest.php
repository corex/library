<?php

use PHPUnit\Framework\TestCase;

class MessagesTest extends TestCase
{
    /**
     * Setup.
     */
    protected function setUp()
    {
        parent::setUp();
        require_once(__DIR__ . '/Helpers/MessagesHelper.php');
    }

    /**
     * Test all.
     */
    public function testAll()
    {
        $this->assertEquals(
            $this->getMessages(),
            MessagesHelper::all()
        );
    }

    /**
     * Test get.
     */
    public function testGet()
    {
        $messages = $this->getMessages();
        $message = MessagesHelper::get(MessagesHelper::SYSTEM_NOT_FOUND);
        $this->assertEquals($messages['SYSTEM_NOT_FOUND'], $message);
    }

    /**
     * Test get with parameters.
     */
    public function testGetWithParameters()
    {
        $check1 = md5(microtime(true));
        $check2 = md5(microtime(true));
        $messages = $this->getMessages();

        // Build check message.
        $checkMessage = $messages['TEST'];
        $checkMessage = str_replace('{param1}', $check1, $checkMessage);
        $checkMessage = str_replace('{param2}', $check2, $checkMessage);

        // Check message.
        $message = MessagesHelper::get(MessagesHelper::TEST, [
            'param1' => $check1,
            'param2' => $check2
        ]);
        $this->assertEquals($checkMessage, $message);
    }

    /**
     * Test get not found.
     */
    public function testGetNotFound()
    {
        $check = md5(microtime(true));
        $message = MessagesHelper::get($check);
        $this->assertNull($message);
    }

    /**
     * Test get code.
     */
    public function testGetCode()
    {
        $this->assertEquals(
            'SYSTEM_NOT_FOUND',
            MessagesHelper::getCode(MessagesHelper::SYSTEM_NOT_FOUND)
        );
    }

    /**
     * Get messages.
     *
     * @return array
     */
    private function getMessages()
    {
        $reflectionClass = new \ReflectionClass(MessagesHelper::class);
        return $reflectionClass->getConstants();
    }
}
