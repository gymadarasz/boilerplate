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
 * AccountActivateTemplateResponder
 *
 * @category  PHP
 * @package   Madsoft\Library\Account
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 */
class AccountActivateTemplateResponder extends TemplateResponder
{
    /**
     * Method getActivateResponse
     *
     * @param AccountActivateArrayResponder $arrayResponder activator
     *
     * @return string
     */
    public function getActivateResponse(
        AccountActivateArrayResponder $arrayResponder
    ): string {
        return $this->setTplfile('activated.phtml')->getResponse(
            $arrayResponder->getActivateResponse()
        );
    }
}
