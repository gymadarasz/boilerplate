<?php declare(strict_types = 1);

/**
 * PHP version 7.4
 *
 * @category  PHP
 * @package   Madsoft\Library\Ctrlr
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 */

namespace Madsoft\Library\Ctrlr;

use Madsoft\Library\Crud;
use Madsoft\Library\Logger;
use Madsoft\Library\Params;
use Madsoft\Library\Template;
use Madsoft\Library\User;
use Madsoft\Library\Validator\Rule\Email;
use Madsoft\Library\Validator\Rule\Number;
use Madsoft\Library\Validator\Rule\Password;
use Madsoft\Library\Validator\Validator;

/**
 * Auth
 *
 * @category  PHP
 * @package   Madsoft\Library\Ctrlr
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 */
class Auth
{
    const LOGIN_DELAY = 0; // TODO: set to 3;
    const ERR_LOGIN = 'Login failed';
    
    protected Crud $crud;
    protected Logger $logger;
    protected User $user;
    protected params $params;
    protected Template $template;
    protected Validator $validator;
    
    /**
     * Method __construct
     *
     * @param Crud      $crud      crud
     * @param Logger    $logger    logger
     * @param User      $user      user
     * @param Params    $params    params
     * @param Template  $template  template
     * @param Validator $validator validator
     */
    public function __construct(
        Crud $crud,
        Logger $logger,
        User $user,
        Params $params,
        Template $template,
        Validator $validator
    ) {
        $this->crud = $crud;
        $this->logger = $logger;
        $this->user = $user;
        $this->params = $params;
        $this->template = $template;
        $this->validator = $validator;
    }
    
    /**
     * Method login
     *
     * @return string
     */
    public function login(): string
    {
        return $this->template->process('login.phtml');
    }
    
    /**
     * Method registry
     *
     * @return string
     */
    public function registry(): string
    {
        return $this->template->process('registry.phtml');
    }
    
    /**
     * Method resend
     *
     * @return string
     */
    public function resend(): string
    {
        // TODO ...
        return 'unimplementd';
    }
    
    /**
     * Method activate
     *
     * @return string
     */
    public function activate(): string
    {
        // TODO ...
        return 'unimplementd';
    }
    
    /**
     * Method reset
     *
     * @return string
     */
    public function reset(): string
    {
        // TODO ...
        return 'unimplementd';
    }
    
    /**
     * Method doLogin
     *
     * @return string
     */
    public function doLogin(): string
    {
        $email = (string)$this->params->get('email');
        
        if (false === sleep(self::LOGIN_DELAY)) {
            return $this->loginError($email, 'Login delay error');
        }
        
        if (!$this->validator->check($email, [Email::class])) {
            return $this->loginError($email, 'Invalid email address');
        }
        
        $password = (string)$this->params->get('password');
        if (!$this->validator->check($password, [Password::class])) {
            return $this->loginError($email, 'Invalid password');
        }
        
        $user = $this->crud->row('user', ['id', 'email'], ['email' => $email]);
        
        if (!$this->validator->check($user->get('id'), [Number::class])) {
            return $this->loginError($email, 'Email not found');
        }
        
        if (!password_verify(
            $this->params->get('password'),
            $user->get('password')
        )
        ) {
            return $this->loginError($email, 'Invalid password');
        }
        
        $this->user->setUid($user->get('id'));
        $this->user->setEmail($user->get('email'));
        
        return $this->template->process(
            'index.phtml',
            [
                'messages' => [
                    'successes' => ['Login succes'],
                    ],
            ]
        );
    }
    
    /**
     * Method loginError
     *
     * @param string $email  email
     * @param string $reason reason
     *
     * @return string
     */
    protected function loginError(string $email, string $reason): string
    {
        $this->logger->error("Login error, email: $email, reason: '$reason'");
        return $this->template->process(
            'login.phtml',
            [
                'messages' => [
                    'errors' => ['Login failed'],
                ],
            ]
        );
    }
    
    /**
     * Method doRegistry
     *
     * @return string
     */
    public function doRegistry(): string
    {
        $errors = [];
        
        $email = (string)$this->params->get('email');
        if (!$this->validator->check($email, [Email::class])) {
            $errors[] = 'Invalid email address';
        }
        
        $password = (string)$this->params->get('password');
        if (!$this->validator->check($password, [Password::class])) {
            $errors[] = 'Invalid password';
        }
        
        $emailRetype = (string)$this->params->get('email_retype');
        if ($email !== $emailRetype) {
            $errors[] = 'Email addresses are not identical';
        }
        
        if ($errors) {
            return $this->template->process(
                'registry.phtml',
                [
                    'messages' => [
                        'errors' => $errors,
                    ],
                    'email' => $email,
                    'password' => $password,
                ]
            );
        }
        
        
        return 'unimplementd';
    }
    
    /**
     * Method doResend
     *
     * @return string
     */
    public function doResend(): string
    {
        // TODO ...
        return 'unimplementd';
    }
    
    /**
     * Method doReset
     *
     * @return string
     */
    public function doReset(): string
    {
        // TODO ...
        return 'unimplementd';
    }
}
