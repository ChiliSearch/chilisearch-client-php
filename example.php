<?php

require_once "vendor/autoload.php";

use SearChili\Client as SearChiliClient;

$client = new SearChiliClient('8fde6f96-ede2-4601-9804-23502916f1e5', 'ba8ea031-7942-466d-8dd7-39285af69466');

$response = $client->site->get();
var_dump($response->toArray());
