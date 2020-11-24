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
                    LoginTemplateResponder::class,
                    'getLoginFormResponse'
                ],
                'index' => [Index::class, 'index'],
                'login' =>
                [
                    LoginTemplateResponder::class,
                    'getLoginFormResponse'
                ],
                'registry' =>
                [
                    RegistryTemplateResponder::class,
                    'getRegistryFormResponse'
                ],
                'resend' =>
                [
                    RegistryTemplateResponder::class,
                    'getResendResponse'
                ],
                'activate' =>
                [
                    ActivateTemplateResponder::class,
                    'getActivateResponse'
                ],
                'reset' =>
                [
                    PasswordResetTemplateResponder::class,
                    'getPasswordResetFormResponse'
                ],
            ],
            'POST' => [
                '' =>
                [
                    LoginTemplateResponder::class,
                    'getLoginResponse'
                ],
                'login' =>
                [
                    LoginTemplateResponder::class,
                    'getLoginResponse'
                ],
                'registry' =>
                [
                    RegistryTemplateResponder::class,
                    'getRegistryResponse'
                ],
                'reset' =>
                [
                    PasswordResetTemplateResponder::class,
                    'getPasswordResetRequestResponse'
                ],
                'change' =>
                [
                    PasswordChangeTemplateResponder::class,
                    'getPasswordChangeResponse'
                ],
            ],
        ],
        'protected' => [
            'GET' => [
                '' => [Index::class, 'restricted'],
                'index' => [Index::class, 'restricted'],
                'logout' =>
                [
                    LogoutTemplateResponder::class,
                    'getLogoutResponse'
                ],
            ],
        ],
    ];
}
