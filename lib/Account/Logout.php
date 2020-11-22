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

use Madsoft\Library\Responder;
use Madsoft\Library\User;

/**
 * Logout
 *
 * @category  PHP
 * @package   Madsoft\Library\Account
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 */
class Logout extends Account
{
    protected Responder $responder;
    protected User $user;
    
    /**
     * Method __construct
     *
     * @param Responder $responder responder
     * @param User      $user      user
     */
    public function __construct(
        Responder $responder,
        User $user
    ) {
        $this->responder = $responder;
        $this->user = $user;
    }
    
    /**
     * Method doLogout
     *
     * @return string
     *
     * @suppress PhanUnreferencedPublicMethod
     */
    public function doLogout(): string
    {
        $this->user->logout();
        
        return $this->responder->getSuccesResponse('login.phtml', 'Logout success');
    }
}
