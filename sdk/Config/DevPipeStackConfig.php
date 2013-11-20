<?php

require_once 'AbstractPipeStackConfig.php';

class DevPipeStackConfig extends AbstractPipeStackConfig {

    protected $clientId = 'YOUR DEV CLIENT ID GOES HERE';

    protected $clientSecret = 'YOUR DEV CLIENT SECRET GOES HERE';

    protected $accessToken = 'OPTIONAL';

    protected $protocol = 'http://';

    protected $hostname = 'apisandbox.pipestack.com';

    protected $format = 'json';

    protected $timeout = 20;

}