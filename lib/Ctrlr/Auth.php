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
use Madsoft\Library\Validator\Email;
use Madsoft\Library\Validator\Password;

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
    protected Email $email;
    protected Password $password;
    protected User $user;
    protected params $params;
    protected Template $template;
    
    /**
     * Method __construct
     *
     * @param Crud     $crud     crud
     * @param Logger   $logger   logger
     * @param Email    $email    email
     * @param Password $password password
     * @param User     $user     user
     * @param Params   $params   params
     * @param Template $template template
     */
    public function __construct(
        Crud $crud,
        Logger $logger,
        Email $email,
        Password $password,
        User $user,
        Params $params,
        Template $template
    ) {
        $this->crud = $crud;
        $this->logger = $logger;
        $this->email = $email;
        $this->password = $password;
        $this->user = $user;
        $this->params = $params;
        $this->template = $template;
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
        // TODO ...
        return 'unimplementd';
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
        if (false === sleep(self::LOGIN_DELAY)) {
            return $this->loginError(['Login delay error']);
        }
        
        $email = (string)$this->params->get('email');
        $errors = $this->email->getErrors($email);
        if ($errors) {
            return $this->loginError($errors);
        }
        
        $password = (string)$this->params->get('password');
        $errors = $this->password->getErrors($password);
        if ($errors) {
            return $this->loginError($errors);
        }
        
        $user = $this->crud->row('user', ['id', 'email'], ['email' => $email]);
        
        if (!$user->get('id')) {
            return $this->loginError(['User is not found, email: ' . $email]);
        }
        
        if (!password_verify(
            $this->params->get('password'),
            $user->get('password')
        )
        ) {
            return $this->loginError(['Invalid password, email: ' . $email]);
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
     * @param string[] $errors errors
     *
     * @return string
     */
    protected function loginError(array $errors): string
    {
        $this->logger->error('Invalid login credentials: ' . implode(', ', $errors));
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
        // TODO ...
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
