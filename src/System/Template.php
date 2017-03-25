<?php

namespace CoRex\Support\System;

use CoRex\Support\Str;

class Template
{
    private $path;
    private $template;
    private $content;
    private $tokens;

    /**
     * Template constructor.
     *
     * @param string $path
     */
    public function __construct($path)
    {
        $this->path = $path;
        $this->clear();
    }

    /**
     * Clear.
     */
    public function clear()
    {
        $this->template = '';
        $this->content = '';
        $this->tokens = [];
    }

    /**
     * Load template content..
     *
     * @param string $templateName Uses dot-notation.
     * @return boolean
     */
    public function loadTemplate($templateName)
    {
        $this->template = $templateName;
        $filename = $this->getFilename($templateName);
        $this->content = File::get($filename, '');
        return File::exist($filename);
    }

    /**
     * Set template content.
     *
     * @param string $content
     */
    public function setTemplate($content)
    {
        $this->template = '';
        $this->content = $content;
    }

    /**
     * Remove empty tokens.
     *
     * @param boolean $removeEmptyTokens Default true.
     * @param boolean $removeLineIfEmptyToken Default true.
     * @return string
     */
    public function render($removeEmptyTokens = true, $removeLineIfEmptyToken = true)
    {
        $content = $this->content;

        if (count($this->tokens) > 0) {
            foreach ($this->tokens as $token => $value) {
                $content = str_replace('{' . $token . '}', $value, $content);
            }
        }

        // Remove empty tokens.
        if ($removeEmptyTokens) {
            $regex = "/{(.*?)}/";
            $matchesCount = intval(preg_match_all($regex, $content, $matches));
            if ($matchesCount > 0) {
                $matches = $matches[0];
                $lines = $this->getContentLines($content);
                foreach ($matches as $match) {
                    foreach ($lines as $index => $line) {
                        if (strpos($line, $match) !== false) {
                            $line = str_replace($match, '', $line);
                            if ($removeLineIfEmptyToken && trim($line) == '') {
                                unset($lines[$index]);
                            }
                        }
                        if (isset($lines[$index])) {
                            $lines[$index] = $line;
                        }
                    }
                }
                $lines = array_values($lines);
                $content = implode("\n", $lines);
            }
        }

        return $content;
    }

    /**
     * Set token.
     *
     * @param string $token
     * @param string $value
     */
    public function setToken($token, $value)
    {
        $this->tokens[$token] = $value;
    }

    /**
     * Set tokens (key/value).
     *
     * @param array $tokenValues
     */
    public function setTokens(array $tokenValues)
    {
        if (!is_array($tokenValues)) {
            return;
        }
        foreach ($tokenValues as $token => $value) {
            $this->setToken($token, $value);
        }
    }

    /**
     * Set token values.
     *
     * @param string $token
     * @param array $values
     * @param string $tokenSeparator
     * @param string $valuePrefix
     * @param string $valueSuffix
     */
    public function setTokenValues($token, array $values, $tokenSeparator = '', $valuePrefix = '', $valueSuffix = '')
    {
        if (count($values) == 0) {
            return;
        }
        $tokens = [];
        foreach ($values as $value) {
            if (trim($value) == '') {
                continue;
            }
            if ($valuePrefix != '') {
                $value = $valuePrefix . $value;
            }
            if ($valueSuffix != '') {
                $value .= $valueSuffix;
            }
            $tokens[] = $value;
        }
        if (count($tokens) > 0) {
            $tokenValue = implode($tokenSeparator, $tokens);
            $this->setToken($token, $tokenValue);
        }
    }

    /**
     * Get filename.
     *
     * @param string $template
     * @param string $path Default false.
     * @return string
     */
    private function getFilename($template, $path = null)
    {
        if ($path === null) {
            $path = $this->path;
        }
        if (Str::endsWith($path, '/')) {
            $path = substr($path, 0, -1);
        }
        $template = str_replace('.', '/', $template);
        $filename = $path . '/' . $template;
        if (!Str::endsWith($filename, '.tpl')) {
            $filename .= '.tpl';
        }
        return $filename;
    }

    /**
     * Get content lines.
     *
     * @param string $content Default null which means current.
     * @return array
     */
    private function getContentLines($content = null)
    {
        if ($content === null) {
            $content = $this->content;
        }
        if (trim($content) == '') {
            return [];
        }
        $content = str_replace("\r", '', $content);
        return explode("\n", $content);
    }
}