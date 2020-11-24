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

use Madsoft\Library\Params;
use Madsoft\Library\Responder\TemplateResponder;
use Madsoft\Library\Session;

/**
 * ActivateTemplateResponder
 *
 * @category  PHP
 * @package   Madsoft\Library\Account
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 */
class ActivateTemplateResponder extends TemplateResponder
{
    /**
     * Method getActivateResponse
     *
     * @param ActivateArrayResponder $arrayResponder activator
     * @param Params                 $params         params
     * @param Session                $session        session
     *
     * @return string
     */
    public function getActivateResponse(
        ActivateArrayResponder $arrayResponder,
        Params $params,
        Session $session
    ): string {
        return $this->setTplfile('activated.phtml')->getResponse(
            $arrayResponder->getActivateResponse($params, $session)
        );
    }
}
