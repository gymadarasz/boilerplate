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
 * Change
 *
 * @category  PHP
 * @package   Madsoft\Library\Account
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 */
class AccountPasswordChangeTemplateResponse extends TemplateResponder
{
    /**
     * Method getChangePasswordResponse
     *
     * @param AccountPasswordChanger $passwordChanger passwordChanger
     *
     * @return string
     */
    public function getChangePasswordResponse(
        AccountPasswordChanger $passwordChanger
    ): string {
        $responseArray = $passwordChanger->changePassword();
        if ($passwordChanger->hasResponseMessageType($responseArray, 'error')) {
            return $this->setTplfile('change.phtml')->getResponse($responseArray);
        }
        return $this->setTplfile('login.phtml')->getResponse($responseArray);
    }
}
