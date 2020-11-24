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

use Madsoft\Library\Responder\TemplateResponder;

/**
 * Login
 *
 * @category  PHP
 * @package   Madsoft\Library\Account
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 */
class AccountLoginTemplateResponder extends TemplateResponder
{
    /**
     * Method getLoginFormResponse
     *
     * @return string
     *
     * @suppress PhanUnreferencedPublicMethod
     */
    public function getLoginFormResponse(): string
    {
        return $this->setTplfile('login.phtml')->getResponse();
    }
    
    /**
     * Method getLoginResponse
     *
     * @param AccountLoginArrayResponder $arrayResponder arrayResponder
     *
     * @return string
     *
     * @suppress PhanUnreferencedPublicMethod
     */
    public function getLoginResponse(
        AccountLoginArrayResponder $arrayResponder
    ): string {
        $arrayResponse = $arrayResponder->getLoginResponse();
        if ($arrayResponder->hasResponseMessageType($arrayResponse, 'error')) {
            return $this->setTplfile('login.phtml')->getResponse($arrayResponse);
        }
        return $this->setTplfile('index.phtml')->getResponse($arrayResponse);
    }
}
