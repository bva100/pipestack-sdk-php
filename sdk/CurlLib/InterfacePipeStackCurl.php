<?php

interface InterfacePipeStackCurl {

    /**
     * Initialize a new cURL resource
     * Clears all set options
     *
     * @return $this
     */
    public function init();

    /**
     * Sets a new cURL option
     *
     * @param string $key
     * @param string $value
     * @return $this
     */
    public function setOption($key, $value);

    /**
     * Get an array of all set options
     *
     * @return array
     */
    public function getOptions();

    /**
     * Clears all set options
     *
     * @return $this
     */
    public function clearOptions();

    /**
     * Make a request with initialized cURL and set options
     * parameters are expected to be x-www-form-urlencoded string, json encoded string or xml encoded string
     *
     * @param string $method
     * @param string $url
     * @param string $params
     * @return mixed
     */
    public function request($method, $url, $params= '');

    /**
     * Get the http status code associated with the request's response
     *
     * @return int
     */
    public function getStatus();

}