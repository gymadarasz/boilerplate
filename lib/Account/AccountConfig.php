<?php declare(strict_types = 1);

/**
 * PHP version 7.4
 *
 * @category  PHP
 * @package   Madsoft\Library\Account
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 */

namespace Madsoft\Library\Account;

use Madsoft\Library\Index;

/**
 * AccountConfig
 *
 * @category  PHP
 * @package   Madsoft\Library\Account
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 */
abstract class AccountConfig
{
    const LOGIN_DELAY = 0; // TODO: set to 3;
    const ROUTES = [
        'public' => [
            'GET' => [
                '' =>
                [
                    AccountLoginTemplateResponder::class,
                    'getLoginFormResponse'
                ],
                'index' => [Index::class, 'index'],
                'login' =>
                [
                    AccountLoginTemplateResponder::class,
                    'getLoginFormResponse'
                ],
                'registry' => [Registry::class, 'registry'],
                'resend' => [Registry::class, 'doResend'],
                'activate' =>
                [
                    AccountActivateTemplateResponder::class,
                    'getActivateResponse'
                ],
                'reset' => [Reset::class, 'reset'],
            ],
            'POST' => [
                '' =>
                [
                    AccountLoginTemplateResponder::class,
                    'getLoginResponse'
                ],
                'login' =>
                [
                    AccountLoginTemplateResponder::class,
                    'getLoginResponse'
                ],
                'registry' => [Registry::class, 'doRegistry'],
                'reset' => [Reset::class, 'doReset'],
                'change' =>
                [
                    AccountPasswordChangeTemplateResponse::class,
                    'getChangePasswordResponse'
                ],
            ],
        ],
        'protected' => [
            'GET' => [
                '' => [Index::class, 'restricted'],
                'index' => [Index::class, 'restricted'],
                'logout' => 
                [
                    AccountLogoutTemplateResponder::class, 
                    'getLogoutResponse'
                ],
            ],
        ],
    ];
}
