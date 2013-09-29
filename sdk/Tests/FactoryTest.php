<?php

require 'sdk/PipeStackFactory.php';

class FactoryTest extends \PHPUnit_Framework_TestCase {

    public function testDevConfig()
    {
        $config = PipeStackFactory::buildConfig('dev');
        $this->assertInstanceOf('DevPipeStackConfig', $config);
    }

    public function testProdConfig()
    {
        $config = PipeStackFactory::buildConfig('Prod');
        $this->assertInstanceOf('ProdPipeStackConfig', $config);
    }

    public function testDefaultDevEnv()
    {
        $PipeStack = PipeStackFactory::build();
        $this->assertInstanceOf('PipeStack', $PipeStack);
    }

    public function testProdEnv()
    {
        $PipeStack = PipeStackFactory::build('Prod');
        $this->assertInstanceOf('PipeStack', $PipeStack);
    }

    public function testCurlLibInjector()
    {
        $PipeStack = PipeStackFactory::build();
        $this->assertInstanceOf('PipeStackCurl', $PipeStack->getCurlLib());
    }

}