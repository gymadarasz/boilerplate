<?php declare(strict_types = 1);

/**
 * PHP version 7.4
 *
 * @category  PHP
 * @package   Madsoft\Library\Test\Account
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 */

namespace Madsoft\Library\Test\Account;

use Madsoft\Library\Account\Change;
use Madsoft\Library\Account\Validator;
use Madsoft\Library\Crud;
use Madsoft\Library\Csrf;
use Madsoft\Library\Encrypter;
use Madsoft\Library\Merger;
use Madsoft\Library\Params;
use Madsoft\Library\Responder;
use Madsoft\Library\Safer;
use Madsoft\Library\Session;
use Madsoft\Library\Template;
use Madsoft\Library\Test;

/**
 * ChangeTest
 *
 * @category  PHP
 * @package   Madsoft\Library\Test\Account
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 *
 * @SuppressWarnings(PHPMD.Superglobals)
 *
 * @suppress PhanUnreferencedClass
 */
class ChangeTest extends Test
{
    
    /**
     * Method testDoChangePasswordDbFails
     *
     * @return void
     *
     * @suppress PhanUndeclaredClassMethod
     * @suppress PhanTypeMismatchArgument
     * @suppress PhanUnreferencedPublicMethod
     */
    public function testDoChangePasswordDbFails(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_GET['token'] = 'a-token';
        $_GET['password'] = 'a-password';
        $params = new Params();
        
        $session = new Session();
        
        $csrf = new Csrf($session, $params);
        
        $safer = new Safer();
        
        $template = new Template($safer, $csrf);
        
        $merger = new Merger();
        
        $crud = $this->getMock(Crud::class);
        $crud->shouldReceive('set')->andReturnFalse();
        
        $validator = $this->getMock(Validator::class);
        $validator->shouldReceive('validateChangePassword')->andReturn([]);
        
        $responder = new Responder($template, $merger);
        
        // @phpstan-ignore-next-line
        $change = new Change($responder, $crud, $params, $validator);
        $encrypter = new Encrypter();
        $result = $change->doChangePassword($encrypter);
        $this->assertStringContains('Password is not saved', $result);
    }
}
