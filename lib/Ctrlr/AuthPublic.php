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

use Madsoft\Library\Config;
use Madsoft\Library\Crud;
use Madsoft\Library\Logger;
use Madsoft\Library\Mailer;
use Madsoft\Library\Params;
use Madsoft\Library\Server;
use Madsoft\Library\Session;
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
class AuthPublic extends Auth
{
    //    protected Server $server;
    protected Session $session;
    protected Crud $crud;
    protected Logger $logger;
    protected User $user;
    protected Params $params;
    protected Template $template;
    protected AuthValidator $validator;
    protected Mailer $mailer;
    protected Config $config;
    
    /**
     * Method __construct
     *
     * @param Session       $session   session
     * @param Crud          $crud      crud
     * @param Logger        $logger    logger
     * @param User          $user      user
     * @param Params        $params    params
     * @param Template      $template  template
     * @param AuthValidator $validator validator
     * @param Mailer        $mailer    mailer
     * @param Config        $config    config
     */
    public function __construct(
        //        Server $server,
        Session $session,
        Crud $crud,
        Logger $logger,
        User $user,
        Params $params,
        Template $template,
        AuthValidator $validator,
        Mailer $mailer,
        Config $config
    ) {
        //        $this->server = $server;
        $this->session = $session;
        $this->crud = $crud;
        $this->logger = $logger;
        $this->user = $user;
        $this->params = $params;
        $this->template = $template;
        $this->validator = $validator;
        $this->mailer = $mailer;
        $this->config = $config;
    }
    
    /**
     * Method login
     *
     * @return string
     */
    public function login(): string
    {
        return $this->template->process($this->getTplsPath() . 'login.phtml');
    }
    
    /**
     * Method registry
     *
     * @return string
     */
    public function registry(): string
    {
        return $this->template->process($this->getTplsPath() . 'registry.phtml');
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
    public function doActivate(): string
    {
        $errors = $this->validator->validateActivate($this->params);
        if ($errors) {
            return $this->activateError('Account activation failed', $errors);
        }
        
        $token = $this->params->get('token');
        
        $user = $this->crud->get('user', ['id', 'active'], ['token' => $token]);
        if (!$user->get('id')) {
            return $this->activateError('Invalid token', []);
        }
        
        if ($user->get('active')) {
            return $this->activateError('User is active already', []);
        }
        
        if (!$this->crud->set('user', ['active' => '1'], ['token' => $token])) {
            return $this->activateError('User activation failed', []);
        }
        
        return $this->template->process(
            $this->getTplsPath() . 'activated.phtml',
            [
                'messages' =>
                [
                    'sucesses' => ['Account is now activated'],
                ]
            ]
        );
    }
    
    /**
     * Method activateError
     *
     * @param string     $error  error
     * @param string[][] $errors errors
     *
     * @return string
     */
    protected function activateError(string $error, array $errors): string
    {
        return $this->template->process(
            $this->getTplsPath() . 'activated.phtml',
            [
                'messages' =>
                [
                    'errors' => [$error],
                ],
                'errors' => $errors,
            ]
        );
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
        
        $this->user->setUid((int)$user->get('id'));
        $this->user->setEmail($user->get('email'));
        
        return $this->template->process(
            $this->getTplsPath() . 'index.phtml',
            [
                'messages' =>
                [
                    'successes' => ['Login success'],
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
            $this->getTplsPath() . 'login.phtml',
            [
                'messages' =>
                [
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
        $errors = $this->validator->validateRegistry($this->params);
        if ($errors) {
            return $this->registryError('Invalid registration data', $errors);
        }
        
        $email = $this->params->get('email');
        $token = $this->generateToken();
        
        $user = $this->crud->get('user', ['email'], ['email' => $email]);
        if ($user->get('email') === $email) {
            return $this->registryError('Email address already registered', $errors);
        }
        
        if (!$this->crud->add(
            'user',
            [
                'email' => $email,
                'hash' => $this->encrypt($this->params->get('password')),
                'token' => $token,
                'active' => '0',
            ]
        )
        ) {
            // TODO: test what if a user already exists
            return $this->registryError('User is not saved', []);
        }
        $this->session->set('resend', ['email' => $email, 'token' => $token]);
        
        if (!$this->sendActivationEmail($email, $token)) {
            return $this->registryError('Activation email is not sent', []);
        }
        
        return $this->template->process($this->getTplsPath() . 'activate.phtml');
    }

    /**
     * Method sendActivationEmail
     *
     * @param string $email email
     * @param string $token token
     *
     * @return bool
     */
    protected function sendActivationEmail(string $email, string $token): bool
    {
        $message = $this->template->process(
            $this->getTplsPath() . 'emails/activation.phtml',
            [
                'base' => $this->config->get('Site')->get('base'),
                'token' => $token,
            ]
        );
        return $this->mailer->send(
            $email,
            'Activate your account',
            $message
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
     * Method registryError
     *
     * @param string     $error  error
     * @param string[][] $errors errors
     *
     * @return string
     */
    protected function registryError(string $error, array $errors): string
    {
        return $this->template->process(
            $this->getTplsPath() . 'registry.phtml',
            [
                'messages' =>
                [
                    'errors' => [$error],
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
