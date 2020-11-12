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

use Madsoft\Library\Csrf;
use Madsoft\Library\Template;

/**
 * Auth
 *
 * @category  PHP
 * @package   Madsoft\Library\Ctrlr
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 */
class Auth
{
    protected Template $template;
    
    /**
     * Method __construct
     *
     * @param Template $template template
     */
    public function __construct(Template $template)
    {
        $this->template = $template;
    }
    
    /**
     * Method login
     *
     * @return string
     */
    public function login(): string
    {
        return $this->template->process('login.phtml');
    }
    
    /**
     * Method registry
     *
     * @return string
     */
    public function registry(): string
    {
        // TODO ...
        return 'unimplementd';
    }
    
    /**
     * Method resend
     *
     * @return string
     */
    public function resend(): string
    {
        // TODO ...
        return 'unimplementd';
    }
    
    /**
     * Method activate
     *
     * @return string
     */
    public function activate(): string
    {
        // TODO ...
        return 'unimplementd';
    }
    
    /**
     * Method reset
     *
     * @return string
     */
    public function reset(): string
    {
        // TODO ...
        return 'unimplementd';
    }
    
    /**
     * Method doLogin
     *
     * @return string
     */
    public function doLogin(): string
    {
        // TODO ...
        return 'unimplementd';
    }
    
    /**
     * Method doRegistry
     *
     * @return string
     */
    public function doRegistry(): string
    {
        // TODO ...
        return 'unimplementd';
    }
    
    /**
     * Method doResend
     *
     * @return string
     */
    public function doResend(): string
    {
        // TODO ...
        return 'unimplementd';
    }
    
    /**
     * Method doReset
     *
     * @return string
     */
    public function doReset(): string
    {
        // TODO ...
        return 'unimplementd';
    }
}
