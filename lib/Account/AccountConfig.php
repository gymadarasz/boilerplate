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
                '' => [Login::class, 'login'],
                'index' => [Index::class, 'index'],
                'login' => [Login::class, 'login'],
                'registry' => [Registry::class, 'registry'],
                'resend' => [Registry::class, 'doResend'],
                'activate' =>
                [
                    AccountActivateTemplateResponder::class, 'getActivateResponse'],
                'reset' => [Reset::class, 'reset'],
            ],
            'POST' => [
                '' => [Login::class, 'doLogin'],
                'login' => [Login::class, 'doLogin'],
                'registry' => [Registry::class, 'doRegistry'],
                'reset' => [Reset::class, 'doReset'],
                'change' => [Change::class, 'doChangePassword'],
            ],
        ],
        'protected' => [
            'GET' => [
                '' => [Index::class, 'restricted'],
                'index' => [Index::class, 'restricted'],
                'logout' => [Logout::class, 'doLogout'],
            ],
        ],
    ];
}
