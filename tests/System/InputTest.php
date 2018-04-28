<?php

use CoRex\Support\System\Input;
use PHPUnit\Framework\TestCase;

class InputTest extends TestCase
{
    const HTTP_HOST = 'test.host';
    const REQUEST_METHOD = 'PaTcH';
    const USER_AGENT = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36';
    const IP = '1.2.3.4';
    const URI = '/test1/test2?firstname=Roger&lastname=Moore&encoded=%28%C3%A6%C3%B8%C3%A5%C3%86%C3%98%C3%85%29';
    const QUERY_STRING = 'firstname=Roger&lastname=Moore&encoded=%28%C3%A6%C3%B8%C3%A5%C3%86%C3%98%C3%85%29';
    const HEADER_CONTENT_TYPE = 'application/type';
    const HEADER_ACCEPT = 'application/type';
    const TEST = 'test';
    const PATH = 'test1/test2';
    const QUERY_ARRAY = [
        'firstname' => 'Roger',
        'lastname' => 'Moore',
        'encoded' => '(æøåÆØÅ)'
    ];

    /**
     * Setup.
     */
    protected function setUp()
    {
        parent::setUp();

        // Setup basic server variables.
        $_SERVER['HTTP_HOST'] = self::HTTP_HOST;
        $_SERVER['REQUEST_METHOD'] = self::REQUEST_METHOD;
        $_SERVER['HTTP_USER_AGENT'] = self::USER_AGENT;
        $_SERVER['REMOTE_ADDR'] = self::IP;
        $_SERVER['REQUEST_URI'] = self::URI;
        $_SERVER['QUERY_STRING'] = self::QUERY_STRING;
        $_SERVER['HTTP_CONTENT_TYPE'] = self::HEADER_CONTENT_TYPE;
        $_SERVER['HTTP_ACCEPT'] = self::HEADER_ACCEPT;
        $_SERVER['HTTP_' . self::TEST] = self::TEST;
    }

    /**
     * Test get base url.
     */
    public function testGetBaseUrl()
    {
        $this->setSslEntries(0);
        $this->assertEquals('http://' . self::HTTP_HOST, Input::getBaseUrl());
        $this->assertEquals('http://' . self::HTTP_HOST . '/' . self::PATH, Input::getBaseUrl(true));
        $this->setSslEntries(1);
        $this->assertEquals('https://' . self::HTTP_HOST, Input::getBaseUrl());
        $this->setSslEntries(2);
        $this->assertEquals('https://' . self::HTTP_HOST, Input::getBaseUrl());
        $this->setSslEntries(3);
        $this->assertEquals('https://' . self::HTTP_HOST, Input::getBaseUrl());
        $this->setSslEntries(4);
        $this->assertEquals('https://' . self::HTTP_HOST, Input::getBaseUrl());
    }

    /**
     * Test get uri.
     */
    public function testGetUri()
    {
        $checkUsername = md5(mt_rand(1, 100000));
        $checkPassword = md5(mt_rand(1, 100000));
        $_SERVER['PHP_AUTH_USER'] = $checkUsername;
        $_SERVER['PHP_AUTH_PW'] = $checkPassword;
        $_SERVER['REQUEST_SCHEME'] = 'https';
        $_SERVER['SERVER_PORT'] = 1234;
        $parts = [
            Input::getScheme(),
            '://',
            Input::getAuthUsername() . ':' . Input::getAuthPassword() . '@',
            Input::getHost(),
            ':' . Input::getPort(),
            '/' . Input::getPath(),
            '?' . self::QUERY_STRING
        ];
        $this->assertEquals(implode('', $parts), Input::getUri());
    }

    /**
     * Test get host.
     */
    public function testGetHost()
    {
        $this->assertEquals(self::HTTP_HOST, Input::getHost());

        $_SERVER['HTTP_X_FORWARDED_HOST'] = 'test1';
        if (isset($_SERVER['HTTP_HOST'])) {
            unset($_SERVER['HTTP_HOST']);
        }
        $this->assertEquals('test1', Input::getHost());

        if (isset($_SERVER['HTTP_X_FORWARDED_HOST'])) {
            unset($_SERVER['HTTP_X_FORWARDED_HOST']);
        }
        $_SERVER['SERVER_NAME'] = 'test2';
        $this->assertEquals('test2', Input::getHost());
    }

    /**
     * Test get host from system.
     */
    public function testGetHostFromSystem()
    {
        if (isset($_SERVER['HTTP_X_FORWARDED_HOST'])) {
            unset($_SERVER['HTTP_X_FORWARDED_HOST']);
        }
        if (isset($_SERVER['SERVER_NAME'])) {
            unset($_SERVER['SERVER_NAME']);
        }
        if (isset($_SERVER['HTTP_HOST'])) {
            unset($_SERVER['HTTP_HOST']);
        }
        $this->assertEquals(gethostname(), Input::getHost());
    }

    /**
     * Test get port.
     */
    public function testGetPort()
    {
        $_SERVER['SERVER_PORT'] = 80;
        $this->assertEquals(80, Input::getPort());

        $_SERVER['SERVER_PORT'] = mt_rand(80, 5000);
        $this->assertEquals($_SERVER['SERVER_PORT'], Input::getPort());

        $_SERVER['SERVER_PORT'] = 0;
        $_SERVER['REQUEST_SCHEME'] = 'unknown';
        $this->assertEquals(0, Input::getPort());
    }

    /**
     * Test get standard port.
     */
    public function testGetStandardPort()
    {
        if (isset($_SERVER['HTTPS'])) {
            unset($_SERVER['HTTPS']);
        }
        if (isset($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
            unset($_SERVER['HTTP_X_FORWARDED_PROTO']);
        }
        if (isset($_SERVER['HTTP_X_FORWARDED_SSL'])) {
            unset($_SERVER['HTTP_X_FORWARDED_SSL']);
        }
        $_SERVER['REQUEST_SCHEME'] = 'http';
        $_SERVER['SERVER_PORT'] = 80;
        $this->assertEquals(80, Input::getStandardPort());

        $_SERVER['REQUEST_SCHEME'] = 'https';
        $_SERVER['SERVER_PORT'] = 443;
        $this->assertEquals(443, Input::getStandardPort());

        $_SERVER['REQUEST_SCHEME'] = 'http';
        $_SERVER['SERVER_PORT'] = 1234;
        $this->assertEquals(80, Input::getStandardPort());

        $this->assertEquals(80, Input::getStandardPort('http'));
        $this->assertEquals(443, Input::getStandardPort('https'));
        $this->assertEquals(21, Input::getStandardPort('ftp'));
        $this->assertEquals(0, Input::getStandardPort('unknown'));
    }

    /**
     * Test get domain.
     */
    public function testGetDomain()
    {
        $this->assertEquals(self::HTTP_HOST, Input::getDomain());
    }

    /**
     * Test get method.
     */
    public function testGetMethod()
    {
        $this->assertEquals(strtoupper(self::REQUEST_METHOD), Input::getMethod());
        $this->assertEquals(strtolower(self::REQUEST_METHOD), Input::getMethod(true));
    }

    /**
     * Test get scheme.
     */
    public function testGetSCheme()
    {
        $this->setSslEntries(0);
        $this->assertEquals('http', Input::getScheme());
        $this->setSslEntries(1);
        $this->assertEquals('https', Input::getScheme());
        $this->setSslEntries(2);
        $this->assertEquals('https', Input::getScheme());
        $this->setSslEntries(3);
        $this->assertEquals('https', Input::getScheme());
        $this->setSslEntries(4);
        $this->assertEquals('https', Input::getScheme());
    }

    /**
     * Test get protocol.
     */
    public function testGetProtocolOtherThanHttp()
    {
        $check = md5(mt_rand(1, 100000));
        $this->setSslEntries(4);
        $_SERVER['REQUEST_SCHEME'] = $check;
        $this->assertEquals($check, Input::getScheme());
    }

    /**
     * Test is ssl.
     */
    public function testIsSsl()
    {
        $this->setSslEntries(0);
        $this->assertFalse(Input::isSsl());
        $this->setSslEntries(1);
        $this->assertTrue(Input::isSsl());
        $this->setSslEntries(2);
        $this->assertTrue(Input::isSsl());
        $this->setSslEntries(3);
        $this->assertTrue(Input::isSsl());
        $this->setSslEntries(4);
        $this->assertTrue(Input::isSsl());
    }

    /**
     * Test get user agent.
     */
    public function testGetUserAgent()
    {
        $_SERVER['HTTP_USER_AGENT'] = self::USER_AGENT;
        $this->assertEquals(self::USER_AGENT, Input::getUserAgent());
        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            unset($_SERVER['HTTP_USER_AGENT']);
        }
        $this->assertEquals('', Input::getUserAgent());
    }

    /**
     * Test get remote ip.
     */
    public function testGetRemoteIp()
    {
        $_SERVER['REMOTE_ADDR'] = self::IP;
        $this->assertEquals(self::IP, Input::getRemoteIp());
        if (isset($_SERVER['REMOTE_ADDR'])) {
            unset($_SERVER['REMOTE_ADDR']);
        }
        $this->assertEquals('', Input::getRemoteIp());
    }

    /**
     * Test get path.
     */
    public function testGetPath()
    {
        $this->assertEquals(self::PATH, Input::getPath());
    }

    /**
     * Test get path segments.
     */
    public function testGetPathSegments()
    {
        $this->assertEquals([
            'segment1' => 'test1',
            'segment2' => 'test2'
        ], Input::getPathSegments(['segment1', 'segment2']));

        $this->assertEquals([], Input::getPathSegments([]));
    }

    /**
     * Test get query.
     */
    public function testGetQuery()
    {
        $this->assertEquals(self::QUERY_ARRAY, Input::getQuery());
        $this->assertEquals(self::QUERY_ARRAY['encoded'], Input::getQuery('encoded'));
        $this->assertNull(Input::getQuery('unknown'));
    }

    /**
     * Test get query string.
     */
    public function testGetQueryString()
    {
        $this->assertEquals(self::QUERY_STRING, Input::getQueryString());
    }

    /**
     * Test get request.
     */
    public function testGetRequest()
    {
        Input::unsetRequest(self::TEST);
        $this->assertNull(Input::getRequest(self::TEST));
        Input::setRequest(self::TEST, self::TEST);
        $this->assertEquals(self::TEST, Input::getRequest(self::TEST));
        $this->assertNull(Input::getRequest('unknown'));
    }

    /**
     * Test set request.
     */
    public function testSetRequest()
    {
        $this->testGetRequest();
    }

    /**
     * Test unset request.
     */
    public function testUnsetRequest()
    {
        $this->testGetRequest();
    }

    /**
     * Test request exist.
     */
    public function testRequestExist()
    {
        Input::unsetRequest(self::TEST);
        $this->assertFalse(Input::requestExist(self::TEST));
        Input::setRequest(self::TEST, self::TEST);
        $this->assertTrue(Input::requestExist(self::TEST));
    }

    /**
     * Test get headers.
     */
    public function testGetHeaders()
    {
        $headers = Input::getHeaders();
        $this->assertEquals(self::TEST, $headers[ucfirst(self::TEST)]);
        $this->assertEquals(self::HEADER_CONTENT_TYPE, $headers['Content-Type']);
        $this->assertEquals(self::HEADER_ACCEPT, $headers['Accept']);
    }

    /**
     * Test get header.
     */
    public function testGetHeader()
    {
        $this->assertEquals(self::TEST, Input::getHeader(self::TEST));
        $this->assertEquals('', Input::getHeader('unknown'));
    }

    /**
     * Test get header Content-Type.
     */
    public function testGetHeaderContentType()
    {
        $this->assertEquals(self::HEADER_CONTENT_TYPE, Input::getHeaderContentType());
    }

    /**
     * Test get header Accept.
     */
    public function testGetHeaderAccept()
    {
        $this->assertEquals(self::HEADER_ACCEPT, Input::getHeaderAccept());
    }

    /**
     * Test is headers sent.
     */
    public function testIsHeadersSent()
    {
        ob_start();
        $this->assertFalse(Input::isHeadersSent());
        print('output');
        $this->assertTrue(Input::isHeadersSent());
        ob_end_clean();
    }

    /**
     * Test get body.
     */
    public function testGetBody()
    {
        $this->assertTrue(true, 'Not possible to test.');
    }

    /**
     * Test get auth username.
     */
    public function testGetAuthUsername()
    {
        // Test not set.
        if (array_key_exists('PHP_AUTH_USER', $_SERVER)) {
            unset($_SERVER['PHP_AUTH_USER']);
        }
        $this->assertNull(Input::getAuthUsername());

        // Test ''.
        $_SERVER['PHP_AUTH_USER'] = null;
        $this->assertNull(Input::getAuthUsername());

        // Test random value.
        $check = md5(mt_rand(1, 100000));
        $_SERVER['PHP_AUTH_USER'] = $check;
        $this->assertEquals($check, Input::getAuthUsername());
    }

    /**
     * Test get auth password.
     */
    public function testGetAuthPassword()
    {
        // Test not set.
        if (array_key_exists('PHP_AUTH_PW', $_SERVER)) {
            unset($_SERVER['PHP_AUTH_PW']);
        }
        $this->assertNull(Input::getAuthPassword());

        // Test ''.
        $_SERVER['PHP_AUTH_PW'] = null;
        $this->assertNull(Input::getAuthPassword());

        // Test random value.
        $check = md5(mt_rand(1, 100000));
        $_SERVER['PHP_AUTH_PW'] = $check;
        $this->assertEquals($check, Input::getAuthPassword());
    }

    /**
     * Set ssl entries.
     *
     * @param integer $entryNumber
     */
    private function setSslEntries($entryNumber)
    {
        if (isset($_SERVER['HTTPS'])) {
            unset($_SERVER['HTTPS']);
        }
        if (isset($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
            unset($_SERVER['HTTP_X_FORWARDED_PROTO']);
        }
        if (isset($_SERVER['HTTP_X_FORWARDED_SSL'])) {
            unset($_SERVER['HTTP_X_FORWARDED_SSL']);
        }
        if ($entryNumber == 1) {
            $_SERVER['HTTPS'] = 'on';
        } elseif ($entryNumber == 2) {
            $_SERVER['HTTPS'] = '1';
        } elseif ($entryNumber == 3) {
            $_SERVER['HTTP_X_FORWARDED_PROTO'] = 'https';
        } elseif ($entryNumber == 4) {
            $_SERVER['HTTP_X_FORWARDED_SSL'] = 'on';
        }
    }
}
