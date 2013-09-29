<?php

require_once 'InterfacePipeStackCurl.php';

class PipeStackCurl implements InterfacePipeStackCurl {

    /**
     * @var resource
     */
    private $ch;

    /**
     * @var array
     */
    private $options;

    public function __construct()
    {
        $this->init();
    }

    /**
     * @return $this
     */
    public function init()
    {
        $this->ch = curl_init();
        $this->clearOptions();
        $this->setOption('RETURNTRANSFER', true)->setOption('TIMEOUT', 30);
    }

    /**
     * Get the current cURL resource
     *
     * @return resource
     */
    public function getCurlResource()
    {
        return $this->ch;
    }

    /**
     * Sets a new cURL option
     *
     * @param string $key
     * @param string $value
     * @return $this
     * @throws InvalidArgumentException
     */
    public function setOption($key, $value)
    {
        $key = strtoupper($key);
        switch($key){
            case 'USERAGENT':
                curl_setopt($this->ch, CURLOPT_USERAGENT, $value);
                break;
            case 'RETURNTRANSFER':
                curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, $value);
                break;
            case 'HTTPHEADER':
                curl_setopt($this->ch, CURLOPT_HTTPHEADER, $value);
                break;
            case 'TIMEOUT':
                curl_setopt($this->ch, CURLOPT_TIMEOUT, $value);
                break;
            case 'URL':
                curl_setopt($this->ch, CURLOPT_URL, $value);
                break;
            case 'POST':
                curl_setopt($this->ch, CURLOPT_POST, $value);
                break;
            case 'CUSTOMREQUEST':
                curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, $value);
                break;
            case 'POSTFIELDS':
                curl_setopt($this->ch, CURLOPT_POSTFIELDS, $value);
                break;
            default:
                throw new InvalidArgumentException('The '.$key.' is not an accepted PipeStack CURL option.');
                break;
        }
        $this->options[$key] = $value;
        return $this;
    }

    /**
     * Get curl options
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Clears all set options
     *
     * @return $this
     */
    public function clearOptions()
    {
        $this->options = array();
        return $this;
    }

    /**
     * Set a HTTP request method
     *
     * @param string $method
     * @throws InvalidArgumentException
     */
    public function setRequestMethod($method)
    {
        $method = strtoupper($method);
        switch($method){
            case 'GET':
                break;
            case 'POST':
                $this->setOption('POST', TRUE);
                break;
            case 'HEAD':
                $this->setOption('CUSTOMREQUEST', 'HEAD');
                break;
            case 'PUT':
                $this->setOption('CUSTOMREQUEST', 'PUT');
                break;
            case 'PATCH':
                $this->setOption('CUSTOMREQUEST', 'PATCH');
                break;
            case 'DELETE':
                $this->setOption('CUSTOMREQUEST', 'DELETE');
                break;
            default:
                throw new InvalidArgumentException('The request method '.$method.' is not supported by the PipeStack cURL library');
                break;
        }
    }

    /**
     * Set parameters to be sent with request
     *
     * @param array $params
     */
    public function setRequestParams(array $params)
    {
        $this->setOption('POSTFIELDS', http_build_query($params));
    }

    /**
     * Make a request with initialized cURL and set options
     *
     * @param string $method
     * @param string $url
     * @param array $params
     * @return mixed
     * @throws RuntimeException
     */
    public function request($method, $url, array $params = array())
    {
        $this->setRequestMethod($method);
        if ( ! empty($params) ){
            $this->setRequestParams($params);
        }
        $this->setOption('URL', $url);
        $response = curl_exec($this->ch);
        if ( $errno = curl_errno($this->ch) ){
            throw new RuntimeException('cURL error while making API call to PipeStack: ' . curl_error($this->ch), $errno);
        }
        return $response;
    }

    /**
     * Return the http status code associated with the previous request
     *
     * @return int
     */
    public function getStatus()
    {
        return curl_getinfo($this->ch, CURLINFO_HTTP_CODE);
    }

    /**
     * close CH resource
     */
    public function __destruct()
    {
        curl_close($this->ch);
    }
    
}