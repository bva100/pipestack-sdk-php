<?php

require 'sdk/Exception/PipeStackException.php';
require 'sdk/Exception/PipeStackRequestException.php';
require 'sdk/Exception/PipeStackPermissionException.php';
require 'sdk/Exception/PipeStackServerException.php';

class PipeStackExceptionTest extends PHPUnit_Framework_TestCase {

    /**
     * @expectedException PipeStackException
     */
    public function testPipeStackException()
    {
        throw new PipeStackException('foobar');
    }

    public function testPipeStackExceptionMessageAndCode()
    {
        $message = 'foobar not found';
        $code = 12345;
        $exception = new PipeStackException($message, $code);
        $this->assertEquals($message, $exception->getMessage());
        $this->assertEquals($code, $exception->getCode());
    }

    /**
     * @expectedException PipeStackRequestException
     */
    public function testPipeStackRequestException()
    {
        throw new PipeStackRequestException('request failed because a foobar was in the way.');
    }

    /**
     * @expectedException PipeStackPermissionException
     */
    public function testPipeStackPermissionException()
    {
        throw new PipeStackPermissionException('request failed because you aren\'t strong enough to have access. Looks like you\'ll need to become a super saiyan first.');
    }

    /**
     * @expectedException PipeStackServerException
     */
    public function testPipeStackServerException()
    {
        throw new PipeStackServerException('request failed because our servers are in the process of melting. Grilled cheese anyone? Please try again later.');
    }

}