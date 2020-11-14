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
use Madsoft\Library\Validator\AuthValidator;

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
    protected Params $params;
    protected Template $template;
    protected AuthValidator $validator;
    
    /**
     * Method __construct
     *
     * @param Crud          $crud      crud
     * @param Logger        $logger    logger
     * @param User          $user      user
     * @param Params        $params    params
     * @param Template      $template  template
     * @param AuthValidator $validator validator
     */
    public function __construct(
        Crud $crud,
        Logger $logger,
        User $user,
        Params $params,
        Template $template,
        AuthValidator $validator
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
        $email = $this->params->get('email', '');
        
        $errors = $this->validator->validateLogin($this->params);
        if ($errors) {
            return $this->loginError($errors, $email);
        }
        
        $user = $this->crud->row(
            'user',
            ['id', 'email', 'password_hash'],
            ['email' => $email]
        );
        
        
        $errors = $this->validator->validateUser(
            $user,
            $this->params->get('password', '')
        );
        
        
        if ($errors) {
            return $this->loginError($errors, $email);
        }
        
        $this->user->setUid($user->get('id'));
        $this->user->setEmail($user->get('email'));
        
        return $this->template->process(
            'index.phtml',
            [
                'messages' =>
                [
                    'successes' => ['Login succes'],
                ],
            ]
        );
    }
    
    /**
     * Method loginError
     *
     * @param string[][] $reasons reasons
     * @param string     $email   email
     *
     * @return string
     */
    protected function loginError(array $reasons, string $email = null): string
    {
        $reasonstr = '';
        foreach ($reasons as $field => $errors) {
            $reasonstr .= " field '$field', error(s): '"
                    . implode("', '", $errors) . "'";
        }
        $this->logger->error(
            "Login error, reason:$reasonstr"
                . ($email ? ", email: '$email'" : '')
        );
        return $this->template->process(
            'login.phtml',
            [
                'messages' =>
                [
                    'errors' => ['Login failed'],
                ],
                'email' => $email,
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
        $errors = $this->validator->validateRegistry($this->params);
        
        return $this->template->process(
            'registry.phtml',
            [
                'messages' =>
                [
                    'errors' => $errors ? ['Registration error'] : [],
                ],
                'params' => $this->params,
                'errors' => $errors,
            ]
        );
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
