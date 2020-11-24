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
use Madsoft\Library\Validator\Validator;
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
 *
 * @suppress PhanUnreferencedClass
 * @suppress PhanUnreferencedPublicClassConstant
 */
class MyScripts
{
    const ROUTES = [
        'protected' => [
            'GET' => [
                'my-scripts/list' => [MyScripts::class, 'viewList'],
                'my-scripts/create' => [MyScripts::class, 'viewCreate'],
            ],
            'POST' => [
                'my-scripts/create' => [MyScripts::class, 'doCreate'],
            ],
        ],
    ];
    
    protected Mysql $mysql;
    protected Crud $crud;
    protected Params $params;
    protected Validator $checker;
    protected TalkbotTemplateResponder $responder;
    
    /**
     * Method __construct
     *
     * @param Mysql                    $mysql     mysql
     * @param Crud                     $crud      crud
     * @param Params                   $params    params
     * @param Validator                $checker   checker
     * @param TalkbotTemplateResponder $responder responder
     */
    public function __construct(
        Mysql $mysql,
        Crud $crud,
        Params $params,
        Validator $checker,
        TalkbotTemplateResponder $responder
    ) {
        $this->mysql = $mysql;
        $this->crud = $crud;
        $this->params = $params;
        $this->checker = $checker;
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
        return $this->responder->setTplfile('my-scripts/list.phtml')->getResponse(
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
        return $this->responder->setTplfile('my-scripts/create.phtml')->getResponse(
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
            return $this->responder->setTplfile(
                'my-scripts/create.html'
            )->getErrorResponse(
                'Invalid parameters',
                $errors
            );
        }
        
        $sid = $this->crud->add('script', ['name' => $this->params->get('name')]);
        if (!$sid) {
            $this->mysql->transRollback();
            return $this->responder->setTplfile(
                'my-scripts/create.phtml'
            )->getErrorResponse();
        }
        
        $myScripts = $this->crud->get('script', ['name'], [], 0);
        return $this->responder->setTplfile(
            'my-scripts/list.phtml'
        )->getSuccessResponse(
            'Script "' . $name . '" is created',
            ['my_scripts' => $myScripts]
        );
    }
}
