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
use Madsoft\Library\Merger;
use Madsoft\Library\Messages;
use Madsoft\Library\Params;
use Madsoft\Library\Responder\ArrayResponder;
use Madsoft\Library\Session;

/**
 * AccountActivator
 *
 * @category  PHP
 * @package   Madsoft\Library\Account
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 */
class AccountActivator extends ArrayResponder
{
    protected Session $session;
    protected Crud $crud;
    protected Params $params;
    protected AccountValidator $validator;
    
    /**
     * Method __construct
     *
     * @param Messages         $messages  messages
     * @param Merger           $merger    merger
     * @param Session          $session   session
     * @param Crud             $crud      crud
     * @param Params           $params    params
     * @param AccountValidator $validator validator
     */
    public function __construct(
        Messages $messages,
        Merger $merger,
        Session $session,
        Crud $crud,
        Params $params,
        AccountValidator $validator
    ) {
        parent::__construct($messages, $merger);
        $this->session = $session;
        $this->crud = $crud;
        $this->params = $params;
        $this->validator = $validator;
    }
    
    /**
     * Method activate
     *
     * @return mixed[]
     */
    public function activate(): array
    {
        $errors = $this->validator->validateActivate($this->params);
        if ($errors) {
            return $this->getErrorResponse(
                'Account activation failed',
                $errors
            );
        }
        
        $token = $this->params->get('token');
        
        $user = $this->crud->get(
            'user',
            ['id', 'active'],
            ['token' => $token],
            1,
            0,
            -1
        );
        if (!$user->get('id')) {
            return $this->getErrorResponse(
                'Invalid token'
            );
        }
        
        if ($user->get('active')) {
            return $this->getErrorResponse(
                'User is active already'
            );
        }
        
        if (!$this->crud->set('user', ['active' => '1'], ['token' => $token])) {
            return $this->getErrorResponse(
                'User activation failed'
            );
        }
        
        $this->session->unset('resend');
        
        return $this->getSuccessResponse(
            'Account is now activated'
        );
    }
}
