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
use Madsoft\Library\Params;
use Madsoft\Library\Template;

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
    protected Crud $crud;
    protected Params $params;
    protected Validator $validator;
    
    /**
     * Method __construct
     *
     * @param Template  $template  template
     * @param Merger    $merger    merger
     * @param Crud      $crud      crud
     * @param Params    $params    params
     * @param Validator $validator validator
     */
    public function __construct(
        Template $template,
        Merger $merger,
        Crud $crud,
        Params $params,
        Validator $validator
    ) {
        parent::__construct($template, $merger);
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
            return $this->getErrorResponse(
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
            return $this->getErrorResponse(
                'change.phtml',
                'Password is not saved',
                [],
                [
                        'token' => $this->params->get('token')
                    ]
            );
        }
        
        return $this->getSuccesResponse('login.phtml', 'Password is changed');
    }
}
