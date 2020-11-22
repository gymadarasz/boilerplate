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

use Madsoft\Library\Crud;
use Madsoft\Library\Logger;
use Madsoft\Library\Params;
use Madsoft\Library\Responder;
use Madsoft\Library\User;

/**
 * Login
 *
 * @category  PHP
 * @package   Madsoft\Library\Account
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 */
class Login extends Account
{
    protected Responder $responder;
    protected Crud $crud;
    protected Logger $logger;
    protected User $user;
    protected Params $params;
    protected Validator $validator;
    
    /**
     * Method __construct
     *
     * @param Responder $responder responder
     * @param Crud      $crud      crud
     * @param Logger    $logger    logger
     * @param User      $user      user
     * @param Params    $params    params
     * @param Validator $validator validator
     */
    public function __construct(
        Responder $responder,
        Crud $crud,
        Logger $logger,
        User $user,
        Params $params,
        Validator $validator
    ) {
        $this->responder = $responder;
        $this->crud = $crud;
        $this->logger = $logger;
        $this->user = $user;
        $this->params = $params;
        $this->validator = $validator;
    }
    
    /**
     * Method login
     *
     * @return string
     *
     * @suppress PhanUnreferencedPublicMethod
     */
    public function login(): string
    {
        return $this->responder->getResponse('login.phtml');
    }
    
    /**
     * Method doLogin
     *
     * @return string
     *
     * @suppress PhanUnreferencedPublicMethod
     */
    public function doLogin(): string
    {
        $email = $this->params->get('email', '');
        
        $errors = $this->validator->validateLogin($this->params);
        if ($errors) {
            return $this->loginError($errors, $email);
        }
        
        $user = $this->crud->get(
            'user',
            ['id', 'email', 'hash'],
            ['email' => $email]
        );
        
        
        $errors = $this->validator->validateUser(
            $user,
            $this->params->get('password', '')
        );
        
        
        if ($errors) {
            return $this->loginError($errors, $email);
        }
        
        $this->user->login((int)$user->get('id'));
        
        return $this->responder->getSuccesResponse('index.phtml', 'Login success');
    }
    
    /**
     * Method loginError
     *
     * @param string[][]  $reasons reasons
     * @param string|null $email   email
     *
     * @return string
     */
    protected function loginError(array $reasons, ?string $email = null): string
    {
        $reasonstr = '';
        foreach ($reasons as $field => $errors) {
            $reasonstr .= " field '$field', error(s): '"
                    . implode("', '", $errors) . "'";
        }
        $this->logger->error(
            "Login error, reason:$reasonstr" . ($email ? ", email: '$email'" : '')
        );
        return $this->responder->getErrorResponse('login.phtml', 'Login failed');
    }
}
