<?php

namespace Madsoft\Talkbot;

use Madsoft\Library\Ctrlr\Auth;
use Madsoft\Library\Ctrlr\Error;
use Madsoft\Library\Ctrlr\Index;
use Madsoft\Library\Invoker;
use Madsoft\Library\Request;

include __DIR__ . '/vendor/autoload.php';

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL | E_STRICT);

echo (new Invoker())->getInstance(Request::class)
        ->setRoutes([
            'public' => [
                'GET' => [
                    '' => [Auth::class, 'login'],
                    'index' => [Index::class, 'index'],
                    'login' => [Auth::class, 'login'],
                    'registry' => [Auth::class, 'registry'],
                    'resend' => [Auth::class, 'resend'],
                    'activate' => [Auth::class, 'activate'],
                    'reset' => [Auth::class, 'reset'],
                ],
                'POST' => [
                    '' => [Auth::class, 'login'],
                    'login' => [Auth::class, 'doLogin'],
                    'registry' => [Auth::class, 'doRegistry'],
                    'resend' => [Auth::class, 'doResend'],
                    'reset' => [Auth::class, 'doReset'],
                ],
            ],
            'protected' => [
                'GET' => [
                    '' => [Index::class, 'restricted'],
                    'password-change' => [Auth::class, 'passwordChange'],
                    'logout' => [Auth::class]
                ],
                'POST' => [
                    'password-change' => [Auth::class, 'doPasswordChange'],
                ],
            ],
        ])
        ->setError([Error::class, 'error'])
        ->process();