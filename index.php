<?php

namespace Madsoft\Talkbot;

use Madsoft\Library\Account\Account;
use Madsoft\Library\Invoker;
use Madsoft\Library\Template;

include __DIR__ . '/vendor/autoload.php';

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL | E_STRICT);

$invoker = isset($this) && isset($this->invoker) ? $this->invoker : new Invoker();
$output = (new Talkbot($invoker))->getOutput([Account::ROUTES, Talkbot::ROUTES]);
echo $output;