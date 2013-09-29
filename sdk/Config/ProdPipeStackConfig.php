<?php

require_once 'AbstractPipeStackConfig.php';

class ProdPipeStackConfig extends AbstractPipeStackConfig {

    protected $clientId = 'YOUR LIVE PRODUCTION CLIENT ID GOES HERE';

    protected $clientSecret = 'YOUR LIVE PRODUCTION CLIENT SECRET GOES HERE';

    protected $accessToken = 'YOUR LIVE PRODUCTION APP ACCESS TOKEN GOES HERE';

    protected $protocol = 'http://';

    protected $format = 'json';

    protected $hostname = 'api.pipestack.com';

    protected $timeout = 20;

}