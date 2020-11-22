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
 * Account
 *
 * @category  PHP
 * @package   Madsoft\Library\Account
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 */
abstract class Account
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
                'activate' => [Activate::class, 'doActivate'],
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
    
    /**
     * Method generateToken
     *
     * @return string
     */
    protected function generateToken(): string
    {
        return urlencode(
            base64_encode($this->encrypt(md5((string)rand(1, 1000000))))
        );
    }
    
    /**
     * Method encrypt
     *
     * @param string $password password
     *
     * @return string
     */
    protected function encrypt(string $password): string
    {
        return (string)password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
    }
}
