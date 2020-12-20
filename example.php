<?php

require_once "vendor/autoload.php";

use SearChili\Alice\Client as SearChiliAliceClient;
use SearChili\Bob\Client as SearChiliBobClient;

## Alice
$aliceClient = new SearChiliAliceClient('88a1e142-f297-4237-bf6c-d7a23609033c');

$response = $aliceClient->entity->search('test');
print_r($response);


## Bob
$bobClient = new SearChiliBobClient('f9c68e53-30c0-4d36-b23e-bd6303aa3c79');

$response = $bobClient->site->get();
print_r($response);

$response = $bobClient->entity->get('1000');
print_r($response);

$response = $bobClient->entity->getAll();
print_r($response);
