<?php

use CoRex\Support\Exceptions\Message;
use PHPUnit\Framework\TestCase;

class MessageTest extends TestCase
{
    /**
     * Setup.
     */
    protected function setUp()
    {
        parent::setUp();
        require_once(dirname(__DIR__) . '/Helpers/MessagesHelper.php');
    }

    /**
     * Test all.
     */
    public function testAll()
    {
        $this->assertEquals(
            $this->getMessages(),
            Message::all(MessagesHelper::class)
        );
    }

    /**
     * Test all class not found.
     */
    public function testAllClassNotFound()
    {
        $classNameUnknown = 'UnknownClass';
        $this->expectException(Exception::class);
        $this->expectExceptionMessage($classNameUnknown);
        Message::all($classNameUnknown);
    }

    /**
     * Test get.
     */
    public function testGet()
    {
        $messages = $this->getMessages();
        $message = Message::get(MessagesHelper::SYSTEM_NOT_FOUND);
        $this->assertEquals($messages[2], $message);
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
        $checkMessage = $messages[9999];
        $checkMessage = str_replace('{param1}', $check1, $checkMessage);
        $checkMessage = str_replace('{param2}', $check2, $checkMessage);

        // Check message.
        $message = Message::get(MessagesHelper::TEST, [
            'param1' => $check1,
            'param2' => $check2
        ]);
        $this->assertEquals($checkMessage, $message);
    }

    /**
     * Test get code.
     */
    public function testGetCode()
    {
        $code = Message::getCode(MessagesHelper::SYSTEM_NOT_FOUND);
        $this->assertEquals(2, $code);
    }

    /**
     * Test get code not found.
     */
    public function testGetCodeNotFound()
    {
        $message = Message::get(MessagesHelper::SYSTEM_NOT_FOUND);
        $this->assertEquals('Not found', $message);
    }

    /**
     * Get messages.
     *
     * @return array
     */
    private function getMessages()
    {
        $messages = [];
        $reflectionClass = new \ReflectionClass(MessagesHelper::class);
        $constants = $reflectionClass->getConstants();
        foreach ($constants as $constant => $message) {
            if (isset($message[0]) && isset($message[1])) {
                $code = $message[0];
                $message = $message[1];
                $messages[$code] = $message;
            }
        }
        return $messages;
    }
}
