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
 * Change
 *
 * @category  PHP
 * @package   Madsoft\Library\Account
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 */
class Change extends Account
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
     * Method doChangePassword
     *
     * @return string
     */
    public function doChangePassword(): string
    {
        $errors = $this->validator->validateChangePassword($this->params);
        if ($errors) {
            return $this->responder->getErrorResponse(
                'change.phtml',
                'Password change failed',
                $errors,
                [
                    'token' => $this->params->get('token')
                ]
            );
        }
        
        if (!$this->crud->set(
            'user',
            [
                'hash' => $this->encrypt($this->params->get('password')),
                'token' => '',
            ],
            [
                'token' => $this->params->get('token'),
            ]
        )
        ) {
            return $this->responder->getErrorResponse(
                'change.phtml',
                'Password is not saved',
                [],
                [
                    'token' => $this->params->get('token')
                ]
            );
        }
        
        return $this->responder->getSuccesResponse(
            'login.phtml',
            'Password is changed'
        );
    }
}
