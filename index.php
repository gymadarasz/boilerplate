<?php

namespace Madsoft\Talkbot;

use Madsoft\Library\Account\Account;
use Madsoft\Library\Error;
use Madsoft\Library\Invoker;
use Madsoft\Library\Request;

include __DIR__ . '/vendor/autoload.php';

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL | E_STRICT);

$invoker = isset($this) && isset($this->invoker) ? $this->invoker : new Invoker();
$output = $invoker
    ->getInstance(Invoker::class)
    ->getInstance(Request::class)
    ->setRoutes(Account::ROUTES)
    ->setError([Error::class, 'error'])
    ->process();
$invoker->free();
echo $output;