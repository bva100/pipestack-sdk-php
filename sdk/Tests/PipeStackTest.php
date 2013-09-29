<?php

require_once 'sdk/PipeStack.php';
require_once 'sdk/Config/DevPipeStackConfig.php';
require_once 'sdk/CurlLib/PipeStackCurl.php';


class PipeStackTest extends \PHPunit_Framework_TestCase {

    private $PipeStack;

    private $config;

    public function setUp()
    {
        $this->config = new LocalFooPipeStackConfig();
        $this->PipeStack = new PipeStack($this->config);
    }

    public function testConfigInjection()
    {
        $this->assertInstanceOf('AbstractPipeStackConfig', $this->PipeStack->getConfig());
    }

    public function testCurlLibInjection()
    {
        $curlLib = $this->getMock('PipeStackCurl');
        $this->PipeStack->setCurlLib($curlLib);
        $this->assertInstanceOf('PipeStackCurl', $this->PipeStack->getCurlLib());
    }

    public function testGetAccessToken()
    {
        $accessToken = $this->config->getAccessToken();
        $this->assertEquals($accessToken, $this->PipeStack->getAccessToken());
    }

    public function testGetProtocol()
    {
        $protocol = $this->config->getProtocol();
        $this->assertEquals($protocol, $this->PipeStack->getProtocol());
    }

    public function testGetHostname()
    {
        $hostname = $this->config->getHostname();
        $this->assertEquals($hostname, $this->PipeStack->getHostname());
    }

    public function testGetTimeout()
    {
        $timeout = $this->config->getTimeout();
        $this->assertEquals($timeout, $this->PipeStack->getTimeout());
    }

    public function testGetFormat()
    {
        $format = $this->config->getFormat();
        $this->assertEquals($format, $this->PipeStack->getFormat());
    }

    public function testGetUserAgent()
    {
        $this->assertContains('PipeStack PHP SDK', $this->PipeStack->getUserAgent());
    }

    public function testGetHeaders()
    {
        $authHeader = 'Authorization: '.$this->config->getAccessToken();
        $headers = $this->PipeStack->getHeaders();
        $this->assertInternalType('array', $headers);
        $this->assertContains($authHeader, $headers);
    }

    public function testGetEndpointUrl()
    {
        $endpoint = 'nodes/1';
        $endpointUrl = 'http://'.$this->config->getHostname().'/v1/nodes/1';
        $this->assertEquals($endpointUrl, $this->PipeStack->getEndpointUrl($endpoint));
    }

    public function testAddHeadersToCurlLib()
    {
        $curlLib = new PipeStackCurl();
        $this->PipeStack->setCurlLib($curlLib);
        $this->assertTrue($this->PipeStack->addHeadersToCurlLib());
    }

    public function testDecodeResponse()
    {
        $responseOrig = new stdClass();
        $responseOrig->foo = 'bar';
        $responseOrig->stack = 'pipe';
        $this->PipeStack->setCurlLib(new PipeStackCurl());
        $response = $this->PipeStack->decodeResponse(json_encode($responseOrig));
        $this->assertEquals($responseOrig, $response);
    }

    public function testGetRequestFormatDefaultFromConfig()
    {
        $formatFromConfig = $this->config->getFormat();
        $this->assertEquals($formatFromConfig, $this->PipeStack->getRequestFormat());
    }

    public function testGetRequest()
    {
        $options = array('format' => 'xml');
        $this->assertEquals('xml', $this->PipeStack->getRequestFormat($options));
    }

}

class LocalFooPipeStackConfig extends DevPipeStackConfig {}