<?php

use CoRex\Support\System\Console;
use CoRex\Support\System\Console\OutputFormatterStyle;
use PHPUnit\Framework\TestCase;

class ConsoleTest extends TestCase
{
    /**
     * Setup.
     */
    protected function setUp()
    {
        parent::setUp();
        Console::setSilent(false);
        Console::setLineLength(80);
        Console::$testValue = null;
    }

    /**
     * Test set silent.
     */
    public function testSetSilent()
    {
        $this->assertFalse(Console::getSilent());
        Console::setSilent();
        $this->assertTrue(Console::getSilent());
    }

    /**
     * Test set line length.
     */
    public function testSetLineLength()
    {
        $this->assertEquals(80, Console::getLineLength());
        Console::setLineLength(120);
        $this->assertEquals(120, Console::getLineLength());
    }

    /**
     * Test write standard.
     *
     * @throws Exception
     */
    public function testWriteStandard()
    {
        ob_start();
        Console::write('test');
        $content = ob_get_clean();
        $this->assertEquals('test', $content);
    }

    /**
     * Test write array.
     *
     * @throws Exception
     */
    public function testWriteArray()
    {
        ob_start();
        Console::write(['test1', 'test2']);
        $content = ob_get_clean();
        $this->assertEquals('test1test2', $content);
    }

    /**
     * Test write length.
     *
     * @throws Exception
     */
    public function testWriteLength()
    {
        ob_start();
        Console::write('test', '', 8);
        $content = ob_get_clean();
        $this->assertEquals('test    ', $content);
    }

    /**
     * Test write suffix.
     *
     * @throws Exception
     */
    public function testWriteSuffix()
    {
        ob_start();
        Console::write(['test1', 'test2'], '', 0, '|');
        $content = ob_get_clean();
        $this->assertEquals('test1|test2|', $content);
    }

    /**
     * Test write style.
     *
     * @throws Exception
     */
    public function testWriteStyle()
    {
        ob_start();
        Console::write('test', 'error');
        $content = ob_get_clean();
        $this->assertEquals(Console\Style::applyStyle('test', 'error'), $content);
    }

    /**
     * Test write silent.
     *
     * @throws Exception
     */
    public function testWriteSilent()
    {
        Console::setSilent();
        ob_start();
        Console::write('test');
        $content = ob_get_clean();
        $this->assertEquals('', $content);
    }

    /**
     * Test writeln standard.
     */
    public function testWritelnStandard()
    {
        ob_start();
        Console::writeln('test');
        $content = ob_get_clean();
        $this->assertEquals("test\n", $content);
    }

    /**
     * Test writeln array.
     */
    public function testWritelnArray()
    {
        ob_start();
        Console::writeln(['test1', 'test2']);
        $content = ob_get_clean();
        $this->assertEquals("test1\ntest2\n", $content);
    }

    /**
     * Test header.
     */
    public function testHeader()
    {
        ob_start();
        Console::header('title');
        $content = ob_get_clean();
        $content = explode("\n", $content);
        $title = str_pad('title', Console::getLineLength(), ' ', STR_PAD_RIGHT);
        $this->assertEquals(Console\Style::applyStyle($title, 'title'), $content[0]);
        $this->assertEquals(str_pad('=', Console::getLineLength(), '=', STR_PAD_RIGHT), $content[1]);
        $this->assertEquals('', $content[2]);
    }

    /**
     * Test separator.
     */
    public function testSeparator()
    {
        ob_start();
        Console::separator();
        $content = ob_get_clean();
        $this->assertEquals(str_repeat('-', Console::getLineLength()) . "\n", $content);
    }

    /**
     * Test separator character.
     */
    public function testSeparatorCharacter()
    {
        ob_start();
        Console::separator('X');
        $content = ob_get_clean();
        $this->assertEquals(str_repeat('X', Console::getLineLength()) . "\n", $content);
    }

    /**
     * Test info.
     */
    public function testInfo()
    {
        ob_start();
        Console::info(__FUNCTION__);
        $content = ob_get_clean();
        $this->assertEquals(Console\Style::applyStyle(__FUNCTION__, 'info') . "\n", $content);
    }

    /**
     * Test error.
     */
    public function testError()
    {
        ob_start();
        Console::error(__FUNCTION__);
        $content = ob_get_clean();
        $this->assertEquals(Console\Style::applyStyle(__FUNCTION__, 'error') . "\n", $content);
    }

    /**
     * Test comment.
     */
    public function testComment()
    {
        ob_start();
        Console::comment(__FUNCTION__);
        $content = ob_get_clean();
        $this->assertEquals(Console\Style::applyStyle(__FUNCTION__, 'comment') . "\n", $content);
    }

    /**
     * Test warning.
     */
    public function testWarning()
    {
        ob_start();
        Console::warning(__FUNCTION__);
        $content = ob_get_clean();
        $this->assertEquals(Console\Style::applyStyle(__FUNCTION__, 'warning') . "\n", $content);
    }

    /**
     * Test title.
     */
    public function testTitle()
    {
        ob_start();
        Console::title(__FUNCTION__);
        $content = ob_get_clean();
        $this->assertEquals(Console\Style::applyStyle(__FUNCTION__, 'title') . "\n", $content);
    }

    /**
     * Test block standard.
     */
    public function testBlockStandard()
    {
        ob_start();
        Console::block('test', 'error');
        $content = ob_get_clean();
        $content = explode("\n", $content);
        $this->assertEquals(
            Console\Style::applyStyle(str_repeat(' ', Console::getLineLength()), 'error'),
            $content[0]
        );
        $this->assertEquals(
            Console\Style::applyStyle(str_pad(' test', Console::getLineLength(), ' ', STR_PAD_RIGHT), 'error'),
            $content[1]
        );
        $this->assertEquals(
            Console\Style::applyStyle(str_repeat(' ', Console::getLineLength()), 'error'),
            $content[2]
        );
    }

    /**
     * Test block array.
     */
    public function testBlockArray()
    {
        ob_start();
        Console::block(['test1', 'test2'], 'error');
        $content = ob_get_clean();
        $content = explode("\n", $content);
        $this->assertEquals(
            Console\Style::applyStyle(str_repeat(' ', Console::getLineLength()), 'error'),
            $content[0]
        );
        $this->assertEquals(
            Console\Style::applyStyle(str_pad(' test1', Console::getLineLength(), ' ', STR_PAD_RIGHT), 'error'),
            $content[1]
        );
        $this->assertEquals(
            Console\Style::applyStyle(str_pad(' test2', Console::getLineLength(), ' ', STR_PAD_RIGHT), 'error'),
            $content[2]
        );
        $this->assertEquals(
            Console\Style::applyStyle(str_repeat(' ', Console::getLineLength()), 'error'),
            $content[3]
        );
    }

    /**
     * Test ask.
     */
    public function testAsk()
    {
        Console::$testValue = md5(time());
        ob_start();
        $value = Console::ask('Are you sure', Console::$testValue);
        $content = ob_get_clean();
        $content = explode("\n", $content);

        $this->assertEquals(Console::$testValue, $value);

        $this->assertEquals('', $content[0]);

        $question = Console\Style::applyStyle(' Are you sure', 'info');
        $defaultValue = Console\Style::applyStyle(Console::$testValue, 'comment');
        $this->assertEquals($question . ' [' . $defaultValue . ']:', $content[1]);

        $this->assertEquals('', $content[2]);
        $this->assertEquals('', $content[3]);
    }

    /**
     * Test confirm 'y'.
     *
     * @param string $testValue Default 'y'.
     */
    public function testConfirmY($testValue = 'y')
    {
        $validValue = in_array($testValue, ['yes', 'y']);
        Console::$testValue = $testValue;
        ob_start();
        $value = Console::confirm('Please confirm', true, in_array($testValue, ['yes', 'y']));
        $content = ob_get_clean();
        $content = explode("\n", $content);

        $this->assertEquals($validValue, $value);

        $this->assertEquals('', $content[0]);

        $question = Console\Style::applyStyle(' Please confirm (yes/no)', 'info');
        $defaultValue = in_array($testValue, ['yes', 'y']) ? 'yes' : 'no';
        $defaultValue = Console\Style::applyStyle($defaultValue, 'comment');
        $this->assertEquals($question . ' [' . $defaultValue . ']:', $content[1]);

        $this->assertEquals('', $content[2]);
        $this->assertEquals('', $content[3]);
    }

    /**
     * Test confirm 'yes'.
     */
    public function testConfirmYes()
    {
        $this->testConfirmY('yes');
    }

    /**
     * Test confirm 'n'.
     */
    public function testConfirmN()
    {
        $this->testConfirmY('n');
    }

    /**
     * Test confirm 'no'.
     */
    public function testConfirmNo()
    {
        $this->testConfirmY('no');
    }

    /**
     * Test secret.
     */
    public function testSecret()
    {
        Console::$testValue = md5(time());
        ob_start();
        $value = Console::secret('Enter password');
        $content = ob_get_clean();
        $content = explode("\n", $content);

        $this->assertEquals(Console::$testValue, $value);

        $this->assertEquals('', $content[0]);

        $question = Console\Style::applyStyle(' Enter password', 'info');
        $this->assertEquals($question . ':', $content[1]);

        $this->assertEquals(' > ', $content[2]);
        $this->assertEquals('', $content[3]);
    }

    /**
     * Test choice.
     */
    public function testChoice()
    {
        Console::$testValue = 1;
        ob_start();
        $value = Console::choice('Choose', ['test1', 'test2']);
        $content = ob_get_clean();
        $content = explode("\n", $content);

        $this->assertEquals(Console::$testValue, $value);

        $this->assertEquals('', $content[0]);

        $question = Console\Style::applyStyle(' Choose', 'info');
        $this->assertEquals($question . ':', $content[1]);

        $choice = Console\Style::applyStyle('1', 'info');
        $this->assertEquals('  [' . $choice . '] test1', $content[2]);

        $choice = Console\Style::applyStyle('2', 'info');
        $this->assertEquals('  [' . $choice . '] test2', $content[3]);

        $this->assertEquals('', $content[4]);
        $this->assertEquals('', $content[5]);
    }

    /**
     * Test table.
     */
    public function testTable()
    {
        ob_start();
        $items = ['test1', 'test2'];
        Console::table($items, ['Test']);
        $content = ob_get_clean();
        $content = explode("\n", $content);
        $this->assertEquals('+-------+', $content[0]);
        $this->assertEquals('| ' . Console\Style::applyStyle('Test ', 'info') . ' |', $content[1]);
        $this->assertEquals('+-------+', $content[2]);
        $this->assertEquals('| test1 |', $content[3]);
        $this->assertEquals('| test2 |', $content[4]);
        $this->assertEquals('+-------+', $content[5]);
    }

    /**
     * Test words.
     */
    public function testWords()
    {
        $words = ['test1', 'test2', 'test3', 'test4'];
        ob_start();
        Console::words($words);
        $content = ob_get_clean();
        $this->assertEquals(implode(', ', $words), $content);
    }

    /**
     * Test throw error.
     *
     * @throws Exception
     */
    public function testThrowError()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage(Console\Style::applyStyle('test', 'error'));
        Console::throwError('test');
    }

    /**
     * Test properties.
     */
    public function testProperties()
    {
        $data = [
            'test1' => '1',
            'test22' => '22'
        ];
        ob_start();
        Console::properties($data);
        $content = ob_get_clean();
        $content = explode("\n", $content);
        $this->assertEquals('test1  : 1', $content[0]);
        $this->assertEquals('test22 : 22', $content[1]);
    }

    /**
     * Test style apply wrong.
     */
    public function testStyleApplyWrong()
    {
        $string = md5(mt_rand(1, 100000));
        $check = Console\Style::apply($string, 'unknown', 'unknown');
        $this->assertEquals($string, $check);
    }

    /**
     * Test style apply.
     */
    public function testStyleApply()
    {
        $string = md5(mt_rand(1, 100000));
        Console\Style::setStyle('testing', 'yellow', 'red');
        $check = Console\Style::apply($string, 'yellow', 'red');

        $style = new OutputFormatterStyle();
        $style->setForeground('yellow');
        $style->setBackground('red');
        $expected = $style->apply($string);

        $this->assertEquals($expected, $check);
    }
}
