<?php

require 'sdk/CurlLib/PipeStackCurl.php';

class PipeStackCurlTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var PipeStackCurl
     */
    private $curlLib;

    public function setUp()
    {
        $this->curlLib = new PipeStackCurl();
    }

    public function testConstructAndInit()
    {
        $this->assertInternalType('resource', $this->curlLib->getCurlResource());
        $options = $this->curlLib->getOptions();
        $this->assertInternalType('array', $options);
        $this->assertEquals($options, array(
            'RETURNTRANSFER' => true,
            'TIMEOUT' => 30,
        ));
    }

    public function testSetOption()
    {
        $userAgent = 'PipeStack Smoke';
        $returnTransfer = true;
        $httpHeader = array('header' => 'bar');
        $timeout = 20;
        $url = 'foo.pipestack.bar.com';
        $post = true;
        $postFields = array('foo' => 'bar');

        $this->curlLib
            ->setOption('userAgent', $userAgent)
            ->setOption('returnTransfer', $returnTransfer)
            ->setOption('httpHeader', $httpHeader)
            ->setOption('timeout', $timeout)
            ->setOption('url', $url)
            ->setOption('post', $post)
            ->setOption('postFields', $postFields);

        $this->assertInternalType('array', $this->curlLib->getOptions());
        $this->assertEquals($this->curlLib->getOptions(), array(
            'USERAGENT' => $userAgent,
            'RETURNTRANSFER' => $returnTransfer,
            'HTTPHEADER' => $httpHeader,
            'TIMEOUT' => $timeout,
            'URL' => $url,
            'POST' => $post,
            'POSTFIELDS' => $postFields
        ));
    }

    public function testSetOptionCustomRequest()
    {
        $this->curlLib->setOption('CUSTOMREQUEST', 'PUT');
        $options = $this->curlLib->getOptions();
        $this->assertEquals('PUT', $options['CUSTOMREQUEST']);
    }

    public function testClearOptions()
    {
        $this->curlLib
            ->setOption('userAgent', 'foo')
            ->setOption('returnTransfer', 'bar')
            ->setOption('httpHeader', array('foobar'));
        $this->curlLib->clearOptions();
        $this->assertEmpty($this->curlLib->getOptions());
    }

    public function testSetRequestMethod()
    {
        $this->curlLib->setRequestMethod('POST');
        $options = $this->curlLib->getOptions();
        $this->assertTRUE($options['POST']);

        $this->curlLib->init();
        $this->curlLib->setRequestMethod('HEAD');
        $options = $this->curlLib->getOptions();
        $this->assertEquals($options['CUSTOMREQUEST'], 'HEAD');

        $this->curlLib->init();
        $this->curlLib->setRequestMethod('PUT');
        $options = $this->curlLib->getOptions();
        $this->assertEquals($options['CUSTOMREQUEST'], 'PUT');

        $this->curlLib->init();
        $this->curlLib->setRequestMethod('PATCH');
        $options = $this->curlLib->getOptions();
        $this->assertEquals($options['CUSTOMREQUEST'], 'PATCH');

        $this->curlLib->init();
        $this->curlLib->setRequestMethod('DELETE');
        $options = $this->curlLib->getOptions();
        $this->assertEquals($options['CUSTOMREQUEST'], 'DELETE');
    }

    public function testSetRequestParams()
    {
        $data = json_encode(array('foo' => 'bar', 'dog' => 'woof'));
        $params = array('data' => $data);
        $this->curlLib->setRequestParams($params);
        $options = $this->curlLib->getOptions();
        $this->assertEquals($params, $options['POSTFIELDS']);
    }

    public function testStatusCode200()
    {
        $this->curlLib->request('GET', 'http://localhost');
        $this->assertEquals($this->curlLib->getStatus(), 200);
    }

    public function testStatusCode404()
    {
        $this->curlLib->request('GET', 'http://localhostapi/demoNotHereEver.php');
        $this->assertEquals($this->curlLib->getStatus(), 404);
    }

    /**
     * @expectedException Exception
     */
    public function testCurlErrorCouldNotFindHost()
    {
        $this->curlLib->request('GET', 'http://localhostfoobarandafooandthiswillneverexistandalwaysfail-forever'.uniqid());
    }



}