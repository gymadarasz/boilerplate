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
use Madsoft\Library\Encrypter;
use Madsoft\Library\Merger;
use Madsoft\Library\Messages;
use Madsoft\Library\Params;
use Madsoft\Library\Responder\ArrayResponder;

/**
 * AccountPasswordChangeArrayResponder
 *
 * @category  PHP
 * @package   Madsoft\Library\Account
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 */
class AccountPasswordChangeArrayResponder extends ArrayResponder
{
    protected Crud $crud;
    protected Params $params;
    protected AccountValidator $validator;
    protected Encrypter $encrypter;
    
    /**
     * Method __construct
     *
     * @param Messages         $messages  messages
     * @param Merger           $merger    merger
     * @param Crud             $crud      crud
     * @param Params           $params    params
     * @param AccountValidator $validator validator
     * @param Encrypter        $encrypter encrypter
     */
    public function __construct(
        Messages $messages,
        Merger $merger,
        Crud $crud,
        Params $params,
        AccountValidator $validator,
        Encrypter $encrypter
    ) {
        parent::__construct($messages, $merger);
        $this->crud = $crud;
        $this->params = $params;
        $this->validator = $validator;
        $this->encrypter = $encrypter;
    }
    
    /**
     * Method getChangePasswordResponse
     *
     * @return mixed[]
     */
    public function getChangePasswordResponse(): array
    {
        $errors = $this->validator->validateChangePassword($this->params);
        if ($errors) {
            return $this->getErrorResponse(
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
                'hash' => $this->encrypter->encrypt($this->params->get('password')),
                'token' => '',
            ],
            [
                'token' => $this->params->get('token'),
            ]
        )
        ) {
            return $this->getErrorResponse(
                'Password is not saved',
                [],
                [
                    'token' => $this->params->get('token')
                ]
            );
        }
        
        return $this->getSuccessResponse(
            'Password is changed'
        );
    }
}
