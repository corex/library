<?php

namespace CoRex\Support\System;

use CoRex\Support\Str;

class Input
{
    /**
     * Get url.
     *
     * @param boolean $addPath Default false.
     * @return string
     */
    public static function getBaseUrl($addPath = false)
    {
        $url = 'http';
        if (self::isSsl()) {
            $url .= 's';
        }
        $url .= '://' . self::getHost();
        if ($addPath) {
            $path = self::getPath();
            if ($path != '') {
                $url .= '/' . $path;
            }
        }
        return $url;
    }

    /**
     * Get uri.
     *
     * @return string
     */
    public static function getUri()
    {
        // Set basic protocol.
        $parts = [$uri = self::getProtocol(), '://'];

        // Set auth username and auth password.
        $authUsername = self::getAuthUsername();
        $authPassword = self::getAuthPassword();
        if ($authUsername !== null && $authPassword !== null) {
            $parts[] = $authUsername . ':' . $authPassword . '@';
        }

        // Set host.
        $parts[] = self::getHost();

        // Set path.
        $path = self::getPath();
        if ($path !== null && $path != '') {
            $parts[] = '/' . $path;
        }

        // Set query.
        $query = Input::getQuery();
        if (is_array($query) && count($query) > 0) {
            $queryString = [];
            foreach ($query as $key => $value) {
                $queryKeyValue = $key . '=';
                if ($value !== null) {
                    if (is_string($value)) {
                        $value = urlencode($value);
                    }
                    $queryKeyValue .= $value;
                }
                $queryString[] = $queryKeyValue;
            }
            if (count($queryString) > 0) {
                $parts[] = '?' . implode('&', $queryString);
            }
        }

        return implode('', $parts);
    }

    /**
     * Get host.
     *
     * @return string
     */
    public static function getHost()
    {
        if (isset($_SERVER['HTTP_HOST'])) {
            return $_SERVER['HTTP_HOST'];
        }
        return '';
    }

    /**
     * Get port (default 80).
     *
     * @return integer
     */
    public static function getPort()
    {
        if (isset($_SERVER['SERVER_PORT'])) {
            return intval($_SERVER['SERVER_PORT']);
        }
        return 80;
    }

    /**
     * Get domain.
     *
     * @return string
     */
    public static function getDomain()
    {
        return self::getHost();
    }

    /**
     * Get method.
     * @param boolean $strLower Default false.
     *
     * @return string
     */
    public static function getMethod($strLower = false)
    {
        $method = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'GET';
        if ($strLower) {
            $method = strtolower($method);
        } else {
            $method = strtoupper($method);
        }
        return $method;
    }

    /**
     * GEt protocol.
     *
     * @return string
     */
    public static function getProtocol()
    {
        $protocol = isset($_SERVER['REQUEST_SCHEME']) ? strtolower($_SERVER['REQUEST_SCHEME']) : 'http';
        if ($protocol == 'http' && self::isSsl()) {
            $protocol .= 's';
        }
        return $protocol;
    }

    /**
     * Is ssl.
     *
     * @return boolean
     */
    public static function isSsl()
    {
        $isSecure = false;
        if (isset($_SERVER['HTTPS']) && in_array($_SERVER['HTTPS'], ['on', '1'])) {
            $isSecure = true;
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
            $isSecure = true;
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on') {
            $isSecure = true;
        }
        return $isSecure;
    }

    /**
     * Get user-agent.
     *
     * @return string
     */
    public static function getUserAgent()
    {
        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            return $_SERVER['HTTP_USER_AGENT'];
        }
        return '';
    }

    /**
     * Get remote ip.
     *
     * @return string
     */
    public static function getRemoteIp()
    {
        if (isset($_SERVER['REMOTE_ADDR'])) {
            return $_SERVER['REMOTE_ADDR'];
        }
        return '';
    }

    /**
     * Get path.
     *
     * @return string
     */
    public static function getPath()
    {
        $uri = $_SERVER['REQUEST_URI'];
        if (strpos($uri, '?') > 0) {
            $uri = substr($uri, 0, strpos($uri, '?'));
        }
        $uri = preg_replace("/^\\/(.*)$/", "$1", $uri);
        $uri = preg_replace("/^(.*)\\/$/", "$1", $uri);
        return $uri;
    }

    /**
     * Split uri into key/value.
     *
     * @param array $keys
     * @return array
     */
    public static function getPathSegments(array $keys)
    {
        if (count($keys) == 0) {
            return [];
        }
        return Str::splitIntoKeyValue(self::getPath(), '/', $keys);
    }

    /**
     * Get query.
     *
     * @param string $name Default '' which means all.
     * @param mixed $defaultValue Default null.
     * @return array|mixed
     */
    public static function getQuery($name = '', $defaultValue = null)
    {
        parse_str($_SERVER['QUERY_STRING'], $queryStringParts);
        if ($name != '') {
            if (isset($queryStringParts[$name])) {
                return $queryStringParts[$name];
            } else {
                return $defaultValue;
            }
        }
        return $queryStringParts;
    }

    /**
     * Get query string.
     *
     * @return string
     */
    public static function getQueryString()
    {
        return isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : '';
    }

    /**
     * Get request.
     *
     * @param string $name
     * @param mixed $defaultValue Default null.
     * @return mixed
     */
    public static function getRequest($name, $defaultValue = null)
    {
        if (isset($_REQUEST[$name])) {
            return $_REQUEST[$name];
        }
        return $defaultValue;
    }

    /**
     * Set request.
     *
     * @param string $name
     * @param mixed $value
     */
    public static function setRequest($name, $value)
    {
        $_REQUEST[$name] = $value;
    }

    /**
     * Unset request.
     *
     * @param string $name
     */
    public static function unsetRequest($name)
    {
        if (self::requestExist($name)) {
            unset($_REQUEST[$name]);
        }
    }

    /**
     * Request exist.
     *
     * @param string $name
     * @return boolean
     */
    public static function requestExist($name)
    {
        return isset($_REQUEST[$name]);
    }

    /**
     * Get headers.
     *
     * @return array|false
     */
    public static function getHeaders()
    {
        return getallheaders();
    }

    /**
     * Get header.
     *
     * @param string $header
     * @param string $defaultValue
     * @return mixed|string
     */
    public static function getHeader($header, $defaultValue = '')
    {
        $headers = self::getHeaders();
        if (is_array($headers) && isset($headers[$header])) {
            return $headers[$header];
        }
        return $defaultValue;
    }

    /**
     * Get header Content-Type.
     *
     * @param string $defaultValue Default ''.
     * @return mixed|string
     */
    public static function getHeaderContentType($defaultValue = '')
    {
        return self::getHeader('Content-Type', $defaultValue);
    }

    /**
     * Get header Accept.
     *
     * @param string $defaultValue Default ''.
     * @return mixed|string
     */
    public static function getHeaderAccept($defaultValue = '')
    {
        return self::getHeader('Accept', $defaultValue);
    }

    /**
     * Is headers sent.
     *
     * @return boolean
     */
    public static function isHeadersSent()
    {
        return strlen(ob_get_contents()) > 0;
    }

    /**
     * Return body.
     *
     * @return string
     */
    public static function getBody()
    {
        return file_get_contents('php://input');
    }

    /**
     * Get auth username.
     *
     * @return string|null
     */
    public static function getAuthUsername()
    {
        $result = null;
        if (isset($_SERVER['PHP_AUTH_USER'])) {
            $result = $_SERVER['PHP_AUTH_USER'];
        }
        if (trim($result) == '') {
            $result = null;
        }
        return $result;
    }

    /**
     * Get auth password.
     *
     * @return string|null
     */
    public static function getAuthPassword()
    {
        $result = null;
        if (isset($_SERVER['PHP_AUTH_PW'])) {
            $result = $_SERVER['PHP_AUTH_PW'];
        }
        if (trim($result) == '') {
            $result = null;
        }
        return $result;
    }
}