<?php

class AbstractPipeStackConfig {

    final public function __construct()
    {
        if ( ! isset($this->clientId) ){
            throw new LogicException('Config class '.get_class($this).' must declare a value for the clientId property. Please see http://pipestack.com/developers/sdk/php for more information.');
        }
        if ( ! isset($this->clientSecret) ){
            throw new LogicException('Config class '.get_class($this).' must declare a value for the client secret property. Please see http://pipestack.com/developers/sdk/php for more information.');
        }
        if ( ! isset($this->protocol) ){
            throw new LogicException('Config class '.get_class($this).' must declare a value for the protocol property. Please see http://pipestack.com/developers/sdk/php for more information.');
        }
        if ( ! isset($this->hostname) ){
            throw new LogicException('Config class '.get_class($this).' must declare a value for the hostname property. Please see http://pipestack.com/developers/sdk/php for more information.');
        }
        if ( ! isset($this->format) ){
            throw new LogicException('Config class '.get_class($this).' must declare a value for the format property. Please see http://pipestack.com/developers/sdk/php for more information.');
        }
        if ( ! isset($this->timeout) ){
            throw new LogicException('Config class '.get_class($this).' must declare a value for the timeout property. Please see http://pipestack.com/developers/sdk/php for more information');
        }
    }

    /**
     * @return string
     */
    final public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * @return string
     */
    final public function getClientId()
    {
        return $this->clientId;
    }

    final public function getClientSecret()
    {
        return $this->clientSecret;
    }

    /**
     * @return string
     */
    final public function getProtocol()
    {
        return $this->protocol;
    }

    /**
     * @return string
     */
    final public function getHostname()
    {
        return $this->hostname;
    }

    /**
     * @return string
     */
    final public function getFormat()
    {
        return $this->format;
    }

    /**
     * @return int
     */
    final public function getTimeout()
    {
        return $this->timeout;
    }

    /**
     * @return array
     */
    final public function getParams()
    {
        return array(
            'clientId' => $this->getClientId(),
            'clientSecret' => $this->getClientSecret(),
            'protocol' => $this->getProtocol(),
            'format' => $this->getFormat(),
            'hostname' => $this->getHostname(),
            'timeout' => $this->getTimeout(),
        );
    }

}