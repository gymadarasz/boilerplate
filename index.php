<?php

namespace Madsoft\Talkbot;

use Madsoft\Library\Invoker;

include __DIR__ . '/vendor/autoload.php';

$request = (new Invoker())->getInstance(Request::class);
$response = $request->getResponse($session, $server);
$output = $response->getOutput();
echo $output;