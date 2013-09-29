#PipeStack SDK for PHP

The **PipeStack SDK for PHP** enables PHP developers to easily work with PipeStack's backend CMS services. The FULL documentation can be found at http://developers.pipestack.com/docs/sdk/php .

## Features

-Provides an easy-to-use HTTP client for all supported PipeStack endpoints and authentication protocols.
-Create multiple config classes to handle different environments
-Provides a built in factory to abstract away the logic associated with instantiating the SDK

## Getting Started
1. **Sign up for PipeStack** – Before you begin, you'll need a PipeStack account. Set one up at http://pipestack.com/user/register .
2. **Minimum requirements** – Be sure your version of PHP is compiled with the cURL extension. The SDK works best with php 5.3.3+.
3. **Install the SDK** – Click the "Download ZIP" button above. Once the files finish downloading, unzip and place the library into your project.
4. **Get your credentials** – Follow the instructions found at http://developers.pipestack.com/docs/basics to get your credentials.
5. **Create your config** – Create your config file by following the instructions found at http://developers.pipestack.com/docs/sdk/php. Essentially you'll be copying and pasting your credentials found in step 4 into a config file called "ProdPipeStackConfig.php" or "DevPipeStackConfig.php".

## Quick Examples

```php
<?php

require '/path/to/PipeStackFactory.php';

// Instantiate a new PipeStack object, using the config file ProdPipeStackConfig.php
$PipeStack = PipeStackFactory::build('Prod');

// Get a collection of nodes
try{
    $response = $PipeStack->get('nodes');
}catch (PipeStackException $e){
    echo  '<h1>EXCEPTION THROWN:</h1><p>', $e->getMessage(), '</p>';
}

echo '<hr><pre>', var_dump($response);

```

```php
<?php

require '/path/to/PipeStackFactory.php';

// Instantiate a new PipeStack object, using the config file DevPipeStackConfig.php
$PipeStack = PipeStackFactory::build('Dev');

// Get a media resource object. Replace 12345 with the ID of your media resource
try{
    $response = $PipeStack->get('media/12345');
}catch (PipeStackException $e){
    echo  '<h1>EXCEPTION THROWN:</h1><p>', $e->getMessage(), '</p>';
}

echo '<h1>' . $response->media->metadata->title . '</h1>';
echo '<img src="'.$response->media->url.'">';

echo '<hr><pre>', var_dump($response);

```

```php
<?php

require '/path/to/PipeStackFactory.php';

// Instantiate a new PipeStack object, using the config file DevPipeStackConfig.php
$PipeStack = PipeStackFactory::build('Dev');

// Create a new node. Replace "your domain here" with your domain
try{
    $response = $PipeStack->create('node', array(
        'title' => 'new node title',
        'domain' => 'your domain here',
        'slug' => 'new-foobar-node',
    ));
}catch (PipeStackException $e){
    echo  '<h1>EXCEPTION THROWN:</h1><p>', $e->getMessage(), '</p>';
}

echo '<hr><pre>', var_dump($response);

```

**Get more examples at http://developers.pipestack.com/docs/sdk/php**