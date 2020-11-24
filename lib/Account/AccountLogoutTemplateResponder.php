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

use Madsoft\Library\Merger;
use Madsoft\Library\Messages;
use Madsoft\Library\Responder\TemplateResponder;
use Madsoft\Library\Template;
use Madsoft\Library\User;

/**
 * AccountLogoutTemplateResponder
 *
 * @category  PHP
 * @package   Madsoft\Library\Account
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 */
class AccountLogoutTemplateResponder extends TemplateResponder
{
    protected User $user;
    
    /**
     * 
     * @param Messages $messages
     * @param Merger $merger
     * @param User $user
     */
    public function __construct(
            Messages $messages,
            Merger $merger,
            Template $template,
        User $user
    ) {
        parent::__construct($messages, $merger, $template);
        $this->user = $user;
    }
    
    /**
     * Method getLogoutResponse
     *
     * @return string
     *
     * @suppress PhanUnreferencedPublicMethod
     */
    public function getLogoutResponse(): string
    {
        $this->user->logout();
        
        return $this->setTplfile('login.phtml')->getSuccessResponse(
            'Logout success'
        );
    }
}
