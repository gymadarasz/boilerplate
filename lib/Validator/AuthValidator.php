<?php declare(strict_types = 1);

/**
 * PHP version 7.4
 *
 * @category  PHP
 * @package   Madsoft\Library\Ctrlr\Validation
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 */

namespace Madsoft\Library\Validator;

use Madsoft\Library\Assoc;
use Madsoft\Library\Ctrlr\AuthPublic;
use Madsoft\Library\Validator\Rule\Email;
use Madsoft\Library\Validator\Rule\Mandatory;
use Madsoft\Library\Validator\Rule\Match;
use Madsoft\Library\Validator\Rule\Number;
use Madsoft\Library\Validator\Rule\Password;
use Madsoft\Library\Validator\Rule\PasswordVerify;
use Madsoft\Library\Validator\Rule\Sleep;
use Madsoft\Library\Validator\Validator;

/**
 * Auth
 *
 * @category  PHP
 * @package   Madsoft\Library\Ctrlr\Validation
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 */
class AuthValidator extends Validator
{
    const PASSWORD_VALIDATION = [
        'minLength' => ['min' => 8,],
        'checkMinLength' => true,
        'checkHasLower' => true,
        'checkHasUpper' => true,
        'checkHasNumber' => true,
        'checkHasSpecChar' => true,
    ];

    /**
     * Method validateLogin
     *
     * @param Assoc $params params
     *
     * @return string[][]
     */
    public function validateLogin(Assoc $params): array
    {
        $errors = $this->getErrors(
            [
                'delay' =>
                [
                    'value' => AuthPublic::LOGIN_DELAY,
                    'rules' =>
                    [
                        //Mandatory::class => null, // TODO add it on live
                        Sleep::class => null,
                    ],
                ],
            ]
        );
        if ($errors) {
            return $errors;
        }
        
        $errors = $this->getFirstError(
            [
                'email' => [
                    'value' => $params->get('email', ''),
                    'rules' => [
                        Mandatory::class => null,
                        Email::class => null,
                    ],
                ],
                'password' => [
                    'value' => $params->get('password', ''),
                    'rules' => [
                        Mandatory::class => null,
                        Password::class => self::PASSWORD_VALIDATION
                    ],
                ],
            ]
        );
        
        return $errors;
    }
    
    /**
     * Method validateUser
     *
     * @param Assoc  $user     user
     * @param string $password password
     *
     * @return string[][]
     */
    public function validateUser(Assoc $user, string $password): array
    {
        $errors = $this->getErrors(
            [
                'id' =>
                [
                    'value' => $user->get('id'),
                    'rules' =>
                    [
                        Mandatory::class => null,
                        Number::class => null,
                    ],
                ],
                'email' =>
                [
                    'value' => $user->get('email'),
                    'rules' =>
                    [
                        Mandatory::class => null,
                        Email::class => null,
                    ],
                ],
                'hash' =>
                [
                    'value' => $user->get('hash'),
                    'rules' =>
                    [
                        Mandatory::class => null,
                        PasswordVerify::class => ['password' => $password],
                    ],
                ],
            ]
        );
        return $errors;
    }
    
    /**
     * Method validateRegistry
     *
     * @param Assoc $params params
     *
     * @return string[][]
     */
    public function validateRegistry(Assoc $params): array
    {
        $email = $params->get('email', '');
        
        $errors = $this->getErrors(
            [
                'email' =>
                [
                    'value' => $email,
                    'rules' =>
                    [
                        Mandatory::class => null,
                        Email::class => null,
                    ],
                ],
                'password' =>
                [
                    'value' => $params->get('password', ''),
                    'rules' =>
                    [
                        Mandatory::class => null,
                        Password::class => self::PASSWORD_VALIDATION,
                    ],
                ],
                'email_retype' => [
                    'value' => $params->get('email_retype', ''),
                    'rules' => [
                        Mandatory::class => null,
                        Match::class => ['equalTo' => $email],
                    ],
                ],
            ],
        );
        
        return $errors;
    }
    
    /**
     * Method validateActivate
     *
     * @param Assoc $params params
     *
     * @return string[][]
     */
    public function validateActivate(Assoc $params): array
    {
        $errors = $this->getErrors(
            [
            'token' => [
                'value' => $params->get('token', ''),
                'rules' => [
                    Mandatory::class => null
                ],
            ],
            ]
        );
        return $errors;
    }
}
