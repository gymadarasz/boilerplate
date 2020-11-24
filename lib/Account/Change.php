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
use Madsoft\Library\Params;
use Madsoft\Library\Responder\TemplateResponder;

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
class Change extends AccountConfig
{
    protected TemplateResponder $responder;
    protected Crud $crud;
    protected Params $params;
    protected AccountValidator $validator;
    
    /**
     * Method __construct
     *
     * @param TemplateResponder $responder responder
     * @param Crud              $crud      crud
     * @param Params            $params    params
     * @param AccountValidator  $validator validator
     */
    public function __construct(
        TemplateResponder $responder,
        Crud $crud,
        Params $params,
        AccountValidator $validator
    ) {
        $this->responder = $responder;
        $this->crud = $crud;
        $this->params = $params;
        $this->validator = $validator;
    }
    
    /**
     * Method doChangePassword
     *
     * @param Encrypter $encrypter encrypter
     *
     * @return string
     */
    public function doChangePassword(Encrypter $encrypter): string
    {
        $errors = $this->validator->validateChangePassword($this->params);
        if ($errors) {
            return $this->responder->setTplfile('change.phtml')->getErrorResponse(
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
                'hash' => $encrypter->encrypt($this->params->get('password')),
                'token' => '',
            ],
            [
                'token' => $this->params->get('token'),
            ]
        )
        ) {
            return $this->responder->setTplfile('change.phtml')->getErrorResponse(
                'Password is not saved',
                [],
                [
                    'token' => $this->params->get('token')
                ]
            );
        }
        
        return $this->responder->setTplfile('login.phtml')->getSuccessResponse(
            'Password is changed'
        );
    }
}
