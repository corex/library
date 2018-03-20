<?php

namespace CoRex\Support\System;

use CoRex\Support\Str;
use CoRex\Support\System\Console\Style;
use CoRex\Support\System\Console\Table;

class Console
{
    /**
     * Test value used in unit testing.
     *
     * @var mixed
     */
    public static $testValue;

    private static $lineLength = 80;
    private static $silent = false;

    /**
     * Set silent.
     *
     * @param boolean $silent Default true.
     */
    public static function setSilent($silent = true)
    {
        self::$silent = $silent;
    }

    /**
     * Get silent.
     *
     * @return boolean
     */
    public static function getSilent()
    {
        return self::$silent;
    }

    /**
     * Set length of line.
     *
     * @param integer $lineLength
     */
    public static function setLineLength($lineLength)
    {
        self::$lineLength = $lineLength;
    }

    /**
     * Get length of line.
     *
     * @return integer
     */
    public static function getLineLength()
    {
        return self::$lineLength;
    }

    /**
     * Write messages.
     *
     * @param string|array $messages
     * @param string $style Default '' which means 'normal'.
     * @param integer $length Default 0 which means not fixed length. $messages NOT shortened.
     * @param string $suffix Default ''.
     */
    public static function write($messages, $style = '', $length = 0, $suffix = '')
    {
        if (self::$silent) {
            return;
        }
        if (!is_array($messages)) {
            $messages = [(string)$messages];
        }
        if (count($messages) > 0) {
            foreach ($messages as $message) {
                if ($length > 0) {
                    $message = str_pad($message, $length, ' ', STR_PAD_RIGHT);
                }
                print(Style::applyStyle($message, $style));
                if ($suffix != '') {
                    print($suffix);
                }
            }
        }
    }

    /**
     * Write messages with linebreak.
     *
     * @param string|array $messages
     * @param string $style Default '' which means normal.
     * @param integer $length Default 0 which means not fixed length.
     */
    public static function writeln($messages, $style = '', $length = 0)
    {
        self::write($messages, $style, $length, "\n");
    }

    /**
     * Write header (title + separator).
     *
     * @param string $title
     * @param string $style Default 'title'.
     */
    public static function header($title, $style = 'title')
    {
        $title = str_pad($title, self::$lineLength, ' ', STR_PAD_RIGHT);
        self::writeln($title, $style);
        self::separator('=');
    }

    /**
     * Write separator-line.
     *
     * @param string $character Default '-'.
     */
    public static function separator($character = '-')
    {
        self::writeln(str_repeat($character, self::$lineLength));
    }

    /**
     * Write info messages.
     *
     * @param string|array $messages
     * @param boolean $linebreak Default false.
     * @param integer $length Default 0 which means not fixed length.
     */
    public static function info($messages, $linebreak = true, $length = 0)
    {
        $separator = $linebreak ? "\n" : '';
        self::write($messages, 'info', $length, $separator);
    }

    /**
     * Write error messages.
     *
     * @param string|array $messages
     * @param boolean $linebreak Default false.
     * @param integer $length Default 0 which means not fixed length.
     */
    public static function error($messages, $linebreak = true, $length = 0)
    {
        $separator = $linebreak ? "\n" : '';
        self::write($messages, 'error', $length, $separator);
    }

    /**
     * Write comment messages.
     *
     * @param string|array $messages
     * @param boolean $linebreak Default false.
     * @param integer $length Default 0 which means not fixed length.
     */
    public static function comment($messages, $linebreak = true, $length = 0)
    {
        $separator = $linebreak ? "\n" : '';
        self::write($messages, 'comment', $length, $separator);
    }

    /**
     * Write warning messages.
     *
     * @param string|array $messages
     * @param boolean $linebreak Default false.
     * @param integer $length Default 0 which means not fixed length.
     */
    public static function warning($messages, $linebreak = true, $length = 0)
    {
        $separator = $linebreak ? "\n" : '';
        self::write($messages, 'warning', $length, $separator);
    }

    /**
     * Write title messages.
     *
     * @param string|array $messages
     * @param boolean $linebreak Default false.
     * @param integer $length Default 0 which means not fixed length.
     */
    public static function title($messages, $linebreak = true, $length = 0)
    {
        $separator = $linebreak ? "\n" : '';
        self::write($messages, 'title', $length, $separator);
    }

    /**
     * Write block messages.
     *
     * @param string|array $messages
     * @param string $style
     */
    public static function block($messages, $style)
    {
        if (is_string($messages)) {
            $messages = [$messages];
        }
        if (count($messages) > 0) {
            self::writeln(str_repeat(' ', self::$lineLength), $style);
            foreach ($messages as $message) {
                $message = ' ' . $message;
                while (strlen($message) < self::$lineLength) {
                    $message .= ' ';
                }
                self::writeln($message, $style);
            }
            self::writeln(str_repeat(' ', self::$lineLength), $style);
        }
    }

    /**
     * Ask question.
     *
     * @param string $question
     * @param mixed $defaultValue Default null.
     * @param boolean $secret Default false.
     * @return string
     */
    public static function ask($question, $defaultValue = null, $secret = false)
    {
        $value = '';
        while (trim($value) == '') {
            self::writeln('');
            self::write(' ' . $question, 'info');
            if ($defaultValue !== null) {
                self::write(' [');
                self::write($defaultValue, 'comment');
                self::write(']');
            }
            self::writeln(':');
            if ($secret) {
                self::write(' > ');
                if (self::$testValue === null) {
                    system('stty -echo');
                    $value = trim(fgets(STDIN));
                    system('stty echo');
                } else {
                    $value = self::$testValue;
                }
            } else {
                if (self::$testValue === null) {
                    $value = readline(' > ');
                } else {
                    $value = self::$testValue;
                }
            }
            if (trim($value) == '') {
                $value = $defaultValue;
            }
            if (trim($value) == '') {
                self::writeln('');
                self::block('[ERROR] A value is required', 'error');
            }
            self::writeln('');
        }
        return trim($value);
    }

    /**
     * Confirm question.
     *
     * @param string $question
     * @param boolean $allowShort Allow to use "y" / "n".
     * @param boolean $defaultValue Default false.
     * @return boolean
     */
    public static function confirm($question, $allowShort, $defaultValue = false)
    {
        $value = $defaultValue ? 'yes' : 'no';
        $value = self::ask($question . ' (yes/no)', $value);
        return $value == 'yes' || ($value == 'y' && $allowShort);
    }

    /**
     * Ask for secret.
     *
     * @param string $question
     * @return string
     */
    public static function secret($question)
    {
        return self::ask($question, null, true);
    }

    /**
     * List choices and ask for choice.
     *
     * @param string $question
     * @param array $choices
     * @param mixed $defaultValue Default null.
     * @return string
     */
    public static function choice($question, array $choices, $defaultValue = null)
    {
        $value = '';
        while (trim($value) == '') {

            // Write prompt.
            self::writeln('');
            self::write(' ' . $question, 'info');
            if ($defaultValue !== null) {
                self::write(' [');
                self::write((string)$defaultValue, 'comment');
                self::write(']');
            }
            self::writeln(':');

            // Write choices.
            if (count($choices) > 0) {
                foreach ($choices as $index => $choice) {
                    self::write('  [');
                    self::write((string)($index + 1), 'comment');
                    self::writeln('] ' . $choice);
                }
            }

            // Input.
            if (self::$testValue === null) {
                $value = readline(' > ');
            } else {
                $value = self::$testValue;
            }
            if (trim($value) == '') {
                $value = $defaultValue;
            }
            if (!isset($choices[intval($value) - 1])) {
                self::writeln('');
                self::block('[ERROR] Value "' . $value . '" is invalid', 'error');
                $value = '';
            } elseif (trim($value) == '') {
                self::writeln('');
                self::block('[ERROR] A value is required', 'error');
            }
            self::writeln('');
        }
        return trim($value);
    }

    /**
     * Show table.
     *
     * @param array $rows
     * @param array $headers Default [].
     */
    public static function table(array $rows, array $headers = [])
    {
        $table = new Table();
        $table->setRows($rows);
        if (count($headers) > 0) {
            $table->setHeaders($headers);
        }
        $output = $table->render();
        self::writeln($output);
    }

    /**
     * Write words.
     *
     * @param array $words
     * @param string $style Default ''.
     * @param string $separator Default ', '.
     */
    public static function words(array $words, $style = '', $separator = ', ')
    {
        self::write(implode($separator, $words), $style);
    }

    /**
     * Throw error-message as exception.
     *
     * @param string $message
     * @throws \Exception
     */
    public static function throwError($message)
    {
        throw new \Exception(Style::applyStyle($message, 'error'));
    }

    /**
     * Properties.
     *
     * @param array $data
     * @param string $separator Default ':'.
     */
    public static function properties(array $data, $separator = ':')
    {
        $keys = array_keys($data);
        $maxLength = max(array_map('strlen', $keys));
        if (count($data) > 0) {
            foreach ($data as $key => $value) {
                $key = Str::padRight($key, $maxLength);
                self::write($key);
                self::write(' ');
                if (Str::length($separator) > 0) {
                    self::write($separator . ' ');
                }
                self::writeln($value);
            }
        }
    }
}