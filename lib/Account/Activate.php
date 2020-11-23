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
use Madsoft\Library\Params;
use Madsoft\Library\Responder;

/**
 * Activate
 *
 * @category  PHP
 * @package   Madsoft\Library\Account
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 */
class Activate extends Account
{
    protected Responder $responder;
    protected Crud $crud;
    protected Params $params;
    protected Validator $validator;
    
    /**
     * Method __construct
     *
     * @param Responder $responder responder
     * @param Crud      $crud      crud
     * @param Params    $params    params
     * @param Validator $validator validator
     */
    public function __construct(
        Responder $responder,
        Crud $crud,
        Params $params,
        Validator $validator
    ) {
        $this->responder = $responder;
        $this->crud = $crud;
        $this->params = $params;
        $this->validator = $validator;
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
            return $this->responder->getErrorResponse(
                'activated.phtml',
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
            return $this->responder->getErrorResponse(
                'activated.phtml',
                'Invalid token',
                []
            );
        }
        
        if ($user->get('active')) {
            return $this->responder->getErrorResponse(
                'activated.phtml',
                'User is active already',
                []
            );
        }
        
        if (!$this->crud->set('user', ['active' => '1'], ['token' => $token])) {
            return $this->responder->getErrorResponse(
                'activated.phtml',
                'User activation failed',
                []
            );
        }
        
        $this->session->unset('resend');
        
        return $this->responder->getSuccesResponse(
            'activated.phtml',
            'Account is now activated'
        );
    }
}
