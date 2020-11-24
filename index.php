<?php

namespace Madsoft\Talkbot;

use Madsoft\Library\Account\AccountConfig;
use Madsoft\Library\App;
use Madsoft\Library\Invoker;

include __DIR__ . '/vendor/autoload.php';

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL | E_STRICT);

$invoker = isset($this) && isset($this->invoker) ? $this->invoker : new Invoker();
$output = (new App($invoker))->getOutput([AccountConfig::ROUTES, MyScripts::ROUTES]);
echo $output;