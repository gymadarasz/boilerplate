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

use Madsoft\Library\Config;
use Madsoft\Library\Crud;
use Madsoft\Library\Mailer;
use Madsoft\Library\Params;
use Madsoft\Library\Responder;
use Madsoft\Library\Session;

/**
 * Registry
 *
 * @category  PHP
 * @package   Madsoft\Library\Account
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 */
class Registry extends Account
{
    protected Responder $responder;
    protected Session $session;
    protected Crud $crud;
    protected Params $params;
    protected Validator $validator;
    protected Mailer $mailer;
    protected Config $config;
    
    /**
     * Method __construct
     *
     * @param Responder $responder responder
     * @param Session   $session   session
     * @param Crud      $crud      crud
     * @param Params    $params    params
     * @param Validator $validator validator
     * @param Mailer    $mailer    mailer
     * @param Config    $config    config
     */
    public function __construct(
        Responder $responder,
        Session $session,
        Crud $crud,
        Params $params,
        Validator $validator,
        Mailer $mailer,
        Config $config
    ) {
        $this->responder = $responder;
        $this->session = $session;
        $this->crud = $crud;
        $this->params = $params;
        $this->validator = $validator;
        $this->mailer = $mailer;
        $this->config = $config;
    }
    
    /**
     * Method registry
     *
     * @return string
     */
    public function registry(): string
    {
        return $this->responder->getResponse('registry.phtml');
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
            return $this->responder->getErrorResponse(
                'registry.phtml',
                'Invalid registration data',
                $errors
            );
        }
        
        $email = $this->params->get('email');
        $token = $this->generateToken();
        
        $user = $this->crud->get('user', ['email'], ['email' => $email]);
        if ($user->get('email') === $email) {
            return $this->responder->getErrorResponse(
                'registry.phtml',
                'Email address already registered',
                $errors
            );
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
            return $this->responder->getErrorResponse(
                'registry.phtml',
                'User is not saved'
            );
        }
        $this->session->set('resend', ['email' => $email, 'token' => $token]);
        
        if (!$this->sendActivationEmail($email, $token)) {
            return $this->responder->getErrorResponse(
                'activate.phtml',
                'Activation email is not sent',
                [],
                $user->getFields()
            );
        }
        
        return $this->responder->getSuccesResponse(
            'activate.phtml',
            'We sent an activation email to your email account, '
                . 'please follow the instructions.'
        );
    }
    
    /**
     * Method doResend
     *
     * @return string
     */
    public function doResend(): string
    {
        $resend = $this->session->get('resend');
        $email = $resend['email'];
        $token = $resend['token'];
        if (!$this->sendActivationEmail($email, $token)) {
            return $this->responder->getErrorResponse(
                'activate.phtml',
                'Activation email is not sent'
            );
        }
        
        return $this->responder->getSuccesResponse(
            'activate.phtml',
            'We re-sent an activation email to your email account, '
                . 'please follow the instructions.'
        );
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
        $message = $this->responder->getResponse(
            'emails/activation.phtml',
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
}
