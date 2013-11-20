<?php

require_once 'AbstractPipeStackConfig.php';

class ProdPipeStackConfig extends AbstractPipeStackConfig {

    protected $clientId = 'YOUR LIVE PRODUCTION CLIENT ID GOES HERE';

    protected $clientSecret = 'YOUR LIVE PRODUCTION CLIENT SECRET GOES HERE';

    protected $accessToken = 'OPTIONAL';

    protected $protocol = 'http://';

    protected $format = 'json';

    protected $hostname = 'api.pipestack.com';

    protected $timeout = 20;

}