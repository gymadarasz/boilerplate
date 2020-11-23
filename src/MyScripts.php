<?php declare(strict_types = 1);

/**
 * PHP version 7.4
 *
 * @category  PHP
 * @package   Madsoft\Talkbot
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 */

namespace Madsoft\Talkbot;

use Madsoft\Library\Crud;
use Madsoft\Library\Mysql;
use Madsoft\Library\Params;
use Madsoft\Library\Session;
use Madsoft\Library\Validator\Checker;
use Madsoft\Library\Validator\Rule\Mandatory;

/**
 * MyScripts
 *
 * @category  PHP
 * @package   Madsoft\Talkbot
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 */
class MyScripts
{
    protected Mysql $mysql;
    protected Crud $crud;
    protected Params $params;
    protected Checker $checker;
    protected Session $session;
    protected TalkbotResponder $responder;
    
    /**
     * Method __construct
     *
     * @param Mysql            $mysql     mysql
     * @param Crud             $crud      crud
     * @param Params           $params    params
     * @param Checker          $checker   checker
     * @param Session          $session   session
     * @param TalkbotResponder $responder responder
     */
    public function __construct(
        Mysql $mysql,
        Crud $crud,
        Params $params,
        Checker $checker,
        Session $session,
        TalkbotResponder $responder
    ) {
        $this->mysql = $mysql;
        $this->crud = $crud;
        $this->params = $params;
        $this->checker = $checker;
        $this->session = $session;
        $this->responder = $responder;
    }
    
    /**
     * Method viewList
     *
     * @return string
     *
     * @suppress PhanUnreferencedPublicMethod
     */
    public function viewList(): string
    {
        return $this->responder->getResponse(
            'my-scripts/list.phtml',
            ['my_scripts' => $this->crud->get('script', ['name'], [], 0)]
        );
    }
    
    /**
     * Method viewCreate
     *
     * @return string
     *
     * @suppress PhanUnreferencedPublicMethod
     */
    public function viewCreate(): string
    {
        return $this->responder->getResponse(
            'my-scripts/create.phtml',
            ['name' => '']
        );
    }
    
    /**
     * Method doCreate
     *
     * @return string
     *
     * @suppress PhanUnreferencedPublicMethod
     */
    public function doCreate(): string
    {
        $name = $this->params->get('name', '');
        $errors = $this->checker->getErrors(
            [
                'name' => [
                    'value' => $name,
                    'rules' => [Mandatory::class => null]
                ]
            ]
        );
        if ($errors) {
            return $this->responder->getErrorResponse(
                'my-scripts/create.html',
                'Invalid parameters',
                $errors
            );
        }

        $this->mysql->transStart();
        
        $sid = $this->crud->add('script', ['name' => $this->params->get('name')]);
        if (!$sid) {
            $this->mysql->transRollback();
            return $this->responder->getErrorResponse('my-scripts/create.phtml');
        }
        
        $uid = $this->session->get('uid');
        $oid = $this->crud->add(
            'ownership',
            [
                'table_name' => 'script',
                'row_id' => (string)$sid,
                'user_id' => (string)$uid,
            ]
        );
        if (!$oid) {
            $this->mysql->transRollback();
            return $this->responder->getErrorResponse('my-scripts/create.phtml');
        }
        
        $this->mysql->transCommit();
        
        $myScripts = $this->crud->get('script', ['name'], [], 0);
        return $this->responder->getSuccesResponse(
            'my-scripts/list.phtml',
            'Script "' . $name . '" is created',
            ['my_scripts' => $myScripts]
        );
    }
}
