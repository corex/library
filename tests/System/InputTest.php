<?php

use CoRex\Support\Str;
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

        // Make sure function getallheaders() exists for tests.
        if (!function_exists('getallheaders')) {
            /**
             * Get all headers.
             *
             * @return array
             */
            function getallheaders()
            {
                return [
                    self::TEST => self::TEST,
                    'Content-Type' => self::HEADER_CONTENT_TYPE,
                    'Accept' => self::HEADER_ACCEPT
                ];
            }
        }
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
     * Test get host.
     */
    public function testGetHost()
    {
        $this->assertEquals(self::HTTP_HOST, Input::getHost());
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
     * Test get protocol.
     */
    public function testGetProtocol()
    {
        $this->setSslEntries(0);
        $this->assertEquals('http', Input::getProtocol());
        $this->setSslEntries(1);
        $this->assertEquals('https', Input::getProtocol());
        $this->setSslEntries(2);
        $this->assertEquals('https', Input::getProtocol());
        $this->setSslEntries(3);
        $this->assertEquals('https', Input::getProtocol());
        $this->setSslEntries(4);
        $this->assertEquals('https', Input::getProtocol());
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
        $this->assertEquals(self::USER_AGENT, Input::getUserAgent());
    }

    /**
     * Test get remote address.
     */
    public function testGetRemoteAddress()
    {
        $this->assertEquals(self::IP, Input::getRemoteAddress());
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
        $uri = 'component/security/user/enable';
        $keys = ['type', 'component', 'controller', 'action'];
        $keyValues = Str::splitIntoKeyValue($uri, '/', $keys);
        $this->assertEquals(4, count($keyValues));
        $this->assertEquals('component', $keyValues['type']);
        $this->assertEquals('security', $keyValues['component']);
        $this->assertEquals('user', $keyValues['controller']);
        $this->assertEquals('enable', $keyValues['action']);
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
        $this->assertEquals(self::TEST, $headers[self::TEST]);
        $this->assertEquals(self::HEADER_CONTENT_TYPE, $headers['Content-Type']);
        $this->assertEquals(self::HEADER_ACCEPT, $headers['Accept']);
    }

    /**
     * Test get header.
     */
    public function testGetHeader()
    {
        $this->assertEquals(self::TEST, Input::getHeader(self::TEST));
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
     * Test get body.
     */
    public function testGetBody()
    {
        $this->assertTrue(true, 'Not possible to test.');
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
