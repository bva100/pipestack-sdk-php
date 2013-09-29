<?php


class PipeStackException extends Exception {

    public function __construct($description = '', $code = 0, $previous = NULL)
    {
        if (!defined('PHP_VERSION_ID')) {
            $version = explode('.', PHP_VERSION);
            define('PHP_VERSION_ID', ($version[0] * 10000 + $version[1] * 100 + $version[2]));
        }
        if (PHP_VERSION_ID < 50300) {
            parent::__construct($description, $code);
        } else {
            parent::__construct($description, $code, $previous);
        }
    }

}