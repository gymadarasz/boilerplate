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
     * Method activate
     *
     * @return string
     */
    public function doActivate(): string
    {
        $errors = $this->validator->validateActivate($this->params);
        if ($errors) {
            return $this->getErrorResponse(
                'activated.phtml',
                'Account activation failed',
                $errors
            );
        }
        
        $token = $this->params->get('token');
        
        $user = $this->crud->get('user', ['id', 'active'], ['token' => $token]);
        if (!$user->get('id')) {
            return $this->getErrorResponse(
                'activated.phtml',
                'Invalid token',
                []
            );
        }
        
        if ($user->get('active')) {
            return $this->getErrorResponse(
                'activated.phtml',
                'User is active already',
                []
            );
        }
        
        if (!$this->crud->set('user', ['active' => '1'], ['token' => $token])) {
            return $this->getErrorResponse(
                'activated.phtml',
                'User activation failed',
                []
            );
        }
        
        return $this->getSuccesResponse(
            'activated.phtml',
            'Account is now activated'
        );
    }
}
