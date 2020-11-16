<?php

namespace Madsoft\Talkbot;

use Madsoft\Library\Ctrlr\AuthProtected;
use Madsoft\Library\Ctrlr\AuthPublic;
use Madsoft\Library\Ctrlr\Error;
use Madsoft\Library\Ctrlr\Index;
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
    ->setRoutes([
        'public' => [
            'GET' => [
                '' => [AuthPublic::class, 'login'],
                'index' => [Index::class, 'index'],
                'login' => [AuthPublic::class, 'login'],
                'registry' => [AuthPublic::class, 'registry'],
                'resend' => [AuthPublic::class, 'resend'],
                'activate' => [AuthPublic::class, 'doActivate'],
                'reset' => [AuthPublic::class, 'reset'],
            ],
            'POST' => [
                '' => [AuthPublic::class, 'doLogin'],
                'login' => [AuthPublic::class, 'doLogin'],
                'registry' => [AuthPublic::class, 'doRegistry'],
                'resend' => [AuthPublic::class, 'doResend'],
                'reset' => [AuthPublic::class, 'doReset'],
            ],
        ],
        'protected' => [
            'GET' => [
                '' => [Index::class, 'restricted'],
                'password-change' => [AuthProtected::class, 'passwordChange'],
                'logout' => [AuthProtected::class, 'doLogout'],
            ],
            'POST' => [
                'password-change' => [AuthProtected::class, 'doPasswordChange'],
            ],
        ],
    ])
    ->setError([Error::class, 'error'])
    ->process();
$invoker->free();
echo $output;