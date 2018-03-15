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
     *
     * @throws ReflectionException
     */
    public function testMessages()
    {
        $this->assertEquals(
            $this->getMessages(),
            MessagesHelper::all()
        );
    }

    /**
     * Test message.
     */
    public function testMessage()
    {
        $check = ['code' => 'SYSTEM_NOT_FOUND', 'status' => 404, 'text' => 'Not found'];
        $this->assertEquals($check, MessagesHelper::message(MessagesHelper::SYSTEM_NOT_FOUND));
    }

    /**
     * Test message not found.
     */
    public function testMessageNotFound()
    {
        $this->assertNull(MessagesHelper::message([1234, 'Unknown']));
    }

    /**
     * Test text.
     *
     * @throws ReflectionException
     */
    public function testText()
    {
        $messages = $this->getMessages();
        $message = MessagesHelper::text(MessagesHelper::SYSTEM_NOT_FOUND);
        $this->assertEquals($messages['SYSTEM_NOT_FOUND']['text'], $message);
    }

    /**
     * Test text with parameters.
     *
     * @throws ReflectionException
     */
    public function testTextWithParameters()
    {
        $check1 = md5(microtime(true));
        $check2 = md5(microtime(true));
        $messages = $this->getMessages();

        // Build check message.
        $checkMessage = $messages['SYSTEM_TEST']['text'];
        $checkMessage = str_replace('{param1}', $check1, $checkMessage);
        $checkMessage = str_replace('{param2}', $check2, $checkMessage);

        // Check message.
        $message = MessagesHelper::text(MessagesHelper::SYSTEM_TEST, [
            'param1' => $check1,
            'param2' => $check2
        ]);
        $this->assertEquals($checkMessage, $message);
    }

    /**
     * Test not found.
     */
    public function testTextNotFound()
    {
        $check = [123, md5(microtime(true))];
        $message = MessagesHelper::text($check);
        $this->assertNull($message);
    }

    /**
     * Test code.
     */
    public function testCode()
    {
        $this->assertEquals('SYSTEM_NOT_FOUND', MessagesHelper::code(MessagesHelper::SYSTEM_NOT_FOUND));
    }

    /**
     * Test status.
     */
    public function testStatus()
    {
        $this->assertEquals(404, MessagesHelper::status(MessagesHelper::SYSTEM_NOT_FOUND));
    }

    /**
     * Get messages.
     *
     * @return array
     * @throws ReflectionException
     */
    private function getMessages()
    {
        $reflectionClass = new \ReflectionClass(MessagesHelper::class);
        $constants = $reflectionClass->getConstants();
        $messages = [];
        foreach ($constants as $constant => $properties) {
            if (empty($properties[0]) || empty($properties[1])) {
                continue;
            }
            $status = $properties[0];
            $message = $properties[1];
            $messages[$constant] = [
                'code' => $constant,
                'status' => $status,
                'text' => $message
            ];
        }
        return $messages;
    }
}
