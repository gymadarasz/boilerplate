<?php declare(strict_types = 1);

/**
 * PHP version 7.4
 *
 * @category  PHP
 * @package   Madsoft\Library\Ctrlr
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 */

namespace Madsoft\Library\Ctrlr;

use Madsoft\Library\Config;
use Madsoft\Library\Crud;
use Madsoft\Library\Logger;
use Madsoft\Library\Mailer;
use Madsoft\Library\Params;
use Madsoft\Library\Server;
use Madsoft\Library\Session;
use Madsoft\Library\Template;
use Madsoft\Library\User;
use Madsoft\Library\Validator\AuthValidator;

/**
 * AuthProtected
 *
 * @category  PHP
 * @package   Madsoft\Library\Ctrlr
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 */
class AuthProtected extends Auth
{
    //    protected Server $server;
    //    protected Session $session;
    //    protected Crud $crud;
    //    protected Logger $logger;
    protected User $user;
    //    protected Params $params;
    protected Template $template;
    //    protected AuthValidator $validator;
    //    protected Mailer $mailer;
    //    protected Config $config;
    
    /**
     * Method __construct
     *
     * @param User     $user     user
     * @param Template $template template
     */
    public function __construct(
        //        Server $server,
        //        Session $session,
        //        Crud $crud,
        //        Logger $logger,
        User $user,
        //        Params $params,
        Template $template
        //        AuthValidator $validator,
        //        Mailer $mailer,
        //        Config $config
    ) {
        //        $this->server = $server;
        //        $this->session = $session;
        //        $this->crud = $crud;
        //        $this->logger = $logger;
        $this->user = $user;
        //        $this->params = $params;
        $this->template = $template;
        //        $this->validator = $validator;
        //        $this->mailer = $mailer;
        //        $this->config = $config;
    }
    
    /**
     * Method doLogout
     *
     * @return string
     */
    public function doLogout(): string
    {
        $this->user->logout();
        
        return $this->template->process(
            $this->getTplsPath() . 'login.phtml',
            [
                'messages' =>
                [
                    'success' => ['Logout success'],
                ],
            ]
        );
    }
}
