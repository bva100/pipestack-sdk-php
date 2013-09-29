<?php

require_once 'PipeStack.php';
require_once 'CurlLib/PipeStackCurl.php';
require_once 'Exception/PipeStackException.php';
require_once 'Exception/PipeStackPermissionException.php';
require_once 'Exception/PipeStackRequestException.php';
require_once 'Exception/PipeStackServerException.php';

class PipeStackFactory {

    /**
     * Creates a new PipeStack SDK object
     * Injects an object which extends the AbstractPipeStackConfig class into a new PipeStack SDK object
     * Injects a PipeStackCurl object
     *
     * @param string $env
     * @return PipeStack
     */
    public static function build($env = 'Prod')
    {
        $PipeStack = new PipeStack(self::buildConfig($env));
        $PipeStack->setCurlLib(new PipeStackCurl());
        return $PipeStack;
    }

    /**
     * Creates a new Config object.
     * The passed $env parameter should align with the desired Config filename. For example, one would pass 'Dev' to instantiate 'DevPipeStackConfig.php' and one would pass 'Prod' to instantiate  'ProdPipeStackConfig.php'
     *
     * @param string $env
     * @return AbstractPipeStackConfig
     * @throws InvalidArgumentException
     */
    public static function buildConfig($env)
    {
        self::validateEnv($env);
        $configName = ucfirst($env).'PipeStackConfig';
        require_once 'Config/'.$configName.'.php';
        return new $configName();
    }

    /**
     * Validates env
     *
     * @param string $env
     * @throws InvalidArgumentException
     */
    private static function validateEnv($env)
    {
        if ( ! is_string($env) ){
            throw new InvalidArgumentException('PipeStackFactory expects the first parameter, $env, to be a string. However, a '.gettype($env).' was passed' );
        }
    }

}