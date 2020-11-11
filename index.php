<?php

namespace Madsoft\Talkbot;

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
                    '' => [Index::class, 'index'],
                ],
            ],
            'protected' => [
                
            ],
        ])
        ->setError([Error::class, 'error'])
        ->process();