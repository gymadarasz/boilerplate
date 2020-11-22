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
use Madsoft\Library\Token;

/**
 * Reset
 *
 * @category  PHP
 * @package   Madsoft\Library\Account
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 */
class Reset extends Account
{
    protected Responder $responder;
    protected Crud $crud;
    protected Params $params;
    protected Validator $validator;
    protected Mailer $mailer;
    protected Config $config;

    /**
     * Method __construct
     *
     * @param Responder $responder responder
     * @param Crud      $crud      crud
     * @param Params    $params    params
     * @param Validator $validator validator
     * @param Mailer    $mailer    mailer
     * @param Config    $config    config
     */
    public function __construct(
        Responder $responder,
        Crud $crud,
        Params $params,
        Validator $validator,
        Mailer $mailer,
        Config $config
    ) {
        $this->responder = $responder;
        $this->crud = $crud;
        $this->params = $params;
        $this->validator = $validator;
        $this->mailer = $mailer;
        $this->config = $config;
    }
    
    /**
     * Method reset
     *
     * @return string
     *
     * @suppress PhanUnreferencedPublicMethod
     */
    public function reset(): string
    {
        $token = $this->params->get('token', '');
        if ($token) {
            $user = $this->crud->get('user', ['id'], ['token' => $token], 1, 0, -1);
            if (!$user->get('id')) {
                return $this->responder->getErrorResponse(
                    'reset.phtml',
                    'Invalid token'
                );
            }
            return $this->responder->getResponse(
                'change.phtml',
                ['token' => $token]
            );
        }
        return $this->responder->getResponse('reset.phtml');
    }
    
    /**
     * Method doReset
     *
     * @param Token $tokengen tokengen
     *
     * @return string
     */
    public function doReset(Token $tokengen): string
    {
        $errors = $this->validator->validateReset($this->params);
        if ($errors) {
            return $this->responder->getErrorResponse(
                'reset.phtml',
                'Reset password failed',
                $errors
            );
        }
        
        $email = (string)$this->params->get('email');
        $user = $this->crud->get('user', ['email'], ['email' => $email], 1, 0, -1);
        if ($user->get('email') !== $email) {
            return $this->responder->getErrorResponse(
                'reset.phtml',
                'Email address not found'
            );
        }
        
        $token = $tokengen->generate();
        if (!$this->crud->set('user', ['token' => $token], ['email' => $email])) {
            return $this->responder->getErrorResponse(
                'reset.phtml',
                'Token is not updated'
            );
        }
        
        if (!$this->sendResetEmail($email, $token)) {
            return $this->responder->getErrorResponse(
                'reset.phtml',
                'Email sending failed'
            );
        }
        
        return $this->responder->getSuccesResponse(
            'reset.phtml',
            'Password reset request email sent'
        );
    }
    
    /**
     * Method sendResetEmail
     *
     * @param string $email email
     * @param string $token token
     *
     * @return bool
     */
    protected function sendResetEmail(string $email, string $token): bool
    {
        $message = $this->responder->getResponse(
            'emails/reset.phtml',
            [
                'base' => $this->config->get('Site')->get('base'),
                'token' => $token,
            ]
        );
        return $this->mailer->send(
            $email,
            'Pasword reset requested',
            $message
        );
    }
}
