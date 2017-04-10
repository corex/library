<?php

use PHPUnit\Framework\TestCase;

class ErrorsTest extends TestCase
{
    /**
     * Setup.
     */
    protected function setUp()
    {
        parent::setUp();
        require_once(__DIR__ . '/Helpers/ErrorsHelper.php');
    }

    /**
     * Test all.
     */
    public function testMessages()
    {
        $this->assertEquals(
            $this->getMessages(),
            ErrorsHelper::messages()
        );
    }

    /**
     * Test message.
     */
    public function testMessage()
    {
        $messages = $this->getMessages();
        $message = ErrorsHelper::message(ErrorsHelper::SYSTEM_NOT_FOUND);
        $this->assertEquals($messages['SYSTEM_NOT_FOUND']['text'], $message);
    }

    /**
     * Test message with parameters.
     */
    public function testMessageWithParameters()
    {
        $check1 = md5(microtime(true));
        $check2 = md5(microtime(true));
        $messages = $this->getMessages();

        // Build check message.
        $checkMessage = $messages['SYSTEM_TEST']['text'];
        $checkMessage = str_replace('{param1}', $check1, $checkMessage);
        $checkMessage = str_replace('{param2}', $check2, $checkMessage);

        // Check message.
        $message = ErrorsHelper::message(ErrorsHelper::SYSTEM_TEST, [
            'param1' => $check1,
            'param2' => $check2
        ]);
        $this->assertEquals($checkMessage, $message);
    }

    /**
     * Test not found.
     */
    public function testMessageNotFound()
    {
        $check = md5(microtime(true));
        $message = ErrorsHelper::message($check);
        $this->assertNull($message);
    }

    /**
     * Test code.
     */
    public function testCode()
    {
        $this->assertEquals('SYSTEM_NOT_FOUND', ErrorsHelper::code(ErrorsHelper::SYSTEM_NOT_FOUND));
    }

    /**
     * Test status.
     */
    public function testStatus()
    {
        $this->assertEquals(404, ErrorsHelper::status(ErrorsHelper::SYSTEM_NOT_FOUND));
    }

    /**
     * Get messages.
     *
     * @return array
     */
    private function getMessages()
    {
        $reflectionClass = new \ReflectionClass(ErrorsHelper::class);
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
