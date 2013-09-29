<?php

class PipeStack  {

    CONST APIVERSION = '1';
    CONST SDKVERSION = '1.0';
    CONST USERAGENT = 'PipeStack PHP SDK version=';

    /**
     * @var AbstractPipeStackConfig
     */
    private $config;

    /**
     * @var InterfacePipeStackCurl
     */
    private $curlLib;

    /**
     * One SHOULD call PipeStackFactory::build() to instantiate a new PipeStack object as opposed to instantiating this class directly
     *
     * @param AbstractPipeStackConfig $config
     */
    public function __construct(AbstractPipeStackConfig $config)
    {
        $this->setConfig($config);
    }

    /**
     * Sets a config object. All configs must extend the AbstractPipeStackConfig class.
     *
     * @param AbstractPipeStackConfig $config
     * @return $this
     */
    public function setConfig(AbstractPipeStackConfig $config)
    {
        $this->config = $config;
        return $this;
    }

    /**
     * @return AbstractPipeStackConfig
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Sets a curlLib class which implements the InterfacePipeStackCurl interface
     *
     * @param InterfacePipeStackCurl $curlLib
     * @return $this
     */
    public function setCurlLib(InterfacePipeStackCurl $curlLib)
    {
        $this->curlLib = $curlLib;
        return $this;
    }

    /**
     * @return InterfacePipeStackCurl
     */
    public function getCurlLib()
    {
        return $this->curlLib;
    }

    /**
     * @return string
     */
    public function getAccessToken()
    {
        return $this->config->getAccessToken();
    }

    /**
     * @return string
     */
    public function getProtocol()
    {
        return $this->config->getProtocol();
    }

    /**
     * @return string
     */
    public function getHostname()
    {
        return $this->config->getHostname();
    }

    /**
     * @return int
     */
    public function getTimeout()
    {
        return $this->config->getTimeout();
    }

    /**
     * @return string
     */
    public function getFormat()
    {
        return $this->config->getFormat();
    }

    /**
     * @return string
     */
    public function getUserAgent()
    {
        return self::USERAGENT.self::SDKVERSION;
    }

    /**
     * Add whichever headers are need for the request to the first parameter, $header. Authorization will automatically be added.
     *
     * @param array $headers
     * @return array
     */
    public function getHeaders(array $headers = array())
    {
        $headers[] = 'Authorization: '.$this->config->getAccessToken();
        return $headers;
    }

    /**
     * @param array $headers
     * @return bool
     */
    public function addHeadersToCurlLib(array $headers = array())
    {
        if ( empty($headers) ){
            $headers = $this->getHeaders();
        }
        $this->curlLib->setOption('HTTPHEADER', $headers);
        return true;
    }

    /**
     * @param string $endpoint
     * @return string
     */
    public function getEndpointUrl($endpoint)
    {
        return $this->getProtocol().$this->getHostname().'/v'.self::APIVERSION.'/'.$endpoint;
    }

    /**
     * Gets format for request. Defaults to config format.
     *
     * @param array $options
     * @return string
     */
    public function getRequestFormat(array $options = array())
    {
        $format = $this->config->getFormat();
        if ( isset($options['format']) ){
            $format = $options['format'];
        }
        return $format;
    }
    
    /**
     * Send a GET request the a PipeStack API endpoint to read a resource
     *
     * @param string $endpoint
     * @param array $options
     * @return mixed
     */
    public function get($endpoint, array $options = array())
    {
        $this->addHeadersToCurlLib();
        $response = $this->curlLib->request('GET', $this->getEndpointUrl($endpoint));
        return $this->decodeResponse($response);
    }

    /**
     * Send a POST request to the PipeStack API endpoint to create a resource
     *
     * @param string $endpoint
     * @param array $params
     * @param array $options
     * @return mixed
     */
    public function create($endpoint, array $params = array(), array $options = array())
    {
        $this->addHeadersToCurlLib();
        $format = $this->getRequestFormat($options);
        if ( $format === 'json' ){
            $params = json_encode($params);
        }
        $response = $this->curlLib->request('POST', $this->getEndpointUrl($endpoint), array('objectParams' => $params));
        return $this->decodeResponse($response);
    }

    /**
     * Send a DELETE request to the PopeStack API endpoint and remove a resource
     *
     * @param string $endpoint
     * @param array $options
     * @return bool
     */
    public function delete($endpoint, array $options = array())
    {
        $this->addHeadersToCurlLib();
        $response = $this->curlLib->request('DELETE', $this->getEndpointUrl($endpoint));
        $this->checkResponseStatus($response, $this->getRequestFormat());
        return true;
    }

    /**
     * Update a resource with a key/value array of properties to change
     * Works in a PATCH pattern
     *
     * @param string $endpoint
     * @param array $params
     * @param array $options
     * @return bool
     */
    public function update($endpoint, array $params, array $options = array())
    {
        $this->addHeadersToCurlLib();
        $format = $this->getRequestFormat($options);
        if ( $format === 'json' ){
            $params = json_encode($params);
        }
        $response = $this->curlLib->request('PATCH', $this->getEndpointUrl($endpoint), array('objectParams' => $params));
        $this->checkResponseStatus($response, $this->getRequestFormat());
        return true;
    }

    /**
     * Decodes a request response. Only supports JSON at this time.
     *
     * @param mixed $response
     * @param string $format
     * @return mixed
     */
    public function decodeResponse($response, $format = '')
    {
        if ( ! $format ){
            $format = $this->config->getFormat();
        }
        $this->checkResponseStatus($response, $format);
        switch($format){
            case 'json':
            case 'default':
                $response =  json_decode($response);
                break;
        }
        return $response;
    }

    /**
     * Check response and throw an exception is appropriate
     *
     * @param $response
     * @param $format
     * @throws PipeStackServerException
     * @throws PipeStackRequestException
     * @throws PipeStackException
     * @throws PipeStackPermissionException
     */
    private function checkResponseStatus($response, $format)
    {
        $status = $this->curlLib->getStatus();
        if ( $status >= 400 ){
            switch($format){
                case 'json':
                default:
                    $data = json_decode($response);
                    if ( $status >= 500 ){
                        if ( $data === null ){
                            throw new PipeStackServerException('A server error has occurred. Please try again soon.');
                        }
                        throw new PipeStackServerException($this->jsonException($data));
                    }
                    switch($status){
                        case 400:
                        case 405:
                            if ( $data === null ){
                                throw new PipeStackRequestException('Invalid request. Please double check parameters and request method.');
                            }
                            throw new PipeStackRequestException($this->jsonException($data));
                        case 401:
                        case 403:
                            if ( $data === null ){
                                throw new PipeStackPermissionException('Invalid permissions and/or authorization. Please check your access token verify endpoint permission requirements.');
                            }
                            throw new PipeStackPermissionException($this->jsonException($data));

                        case 404:
                            if ( $data === null ){
                                throw new PipeStackRequestException('You have requested an endpoint which does not exist. Please double check the endpoint parameter and try again.');
                            }
                            throw new PipeStackRequestException($this->jsonException($data));
                            break;
                        default:
                            if ( $data === null ){
                                throw new PipeStackException('An unknown PipeStack API error has occurred. Please contact PipeStack customer service.');
                            }
                            throw new PipeStackException($this->jsonException($data));
                            break;
                    }
                    break;
            }
        }
    }

    /**
     * Convert an error with a json body into a string for an exception message
     *
     * @param $data
     * @return string
     */
    private function jsonException($data)
    {
        $meta = $data->meta;
        return $meta->status.' '.$meta->description.' '.$meta->message.' For more info please visit: '.$meta->moreInfo;
    }

}