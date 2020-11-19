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
use Madsoft\Library\Template;
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
    protected User $user;
    
    /**
     * Method __construct
     *
     * @param Template $template template
     * @param Merger   $merger   merger
     * @param User     $user     user
     */
    public function __construct(
        Template $template,
        Merger $merger,
        User $user
    ) {
        parent::__construct($template, $merger);
        $this->user = $user;
    }
    
    /**
     * Method doLogout
     *
     * @return string
     */
    public function doLogout(): string
    {
        $this->user->logout();
        
        return $this->getSuccesResponse('login.phtml', 'Logout success');
    }
}
