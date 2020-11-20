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

use Madsoft\Library\Account\Registry;
use Madsoft\Library\Account\Validator;
use Madsoft\Library\Assoc;
use Madsoft\Library\Config;
use Madsoft\Library\Crud;
use Madsoft\Library\Csrf;
use Madsoft\Library\Mailer;
use Madsoft\Library\Merger;
use Madsoft\Library\Params;
use Madsoft\Library\Safer;
use Madsoft\Library\Session;
use Madsoft\Library\Template;
use Madsoft\Library\Test;
use RuntimeException;

/**
 * RegistryTest
 *
 * @category  PHP
 * @package   Madsoft\Library\Test\Account
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 *
 * @SuppressWarnings(PHPMD.Superglobals)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 *
 * @suppress PhanUnreferencedClass
 */
class RegistryTest extends Test
{
    /**
     * Method testDoRegistryDbFails
     *
     * @return void
     *
     * @suppress PhanUndeclaredClassMethod
     * @suppress PhanTypeMismatchArgument
     * @suppress PhanUnreferencedPublicMethod
     * @suppress PhanUnreferencedClosure
     */
    public function testDoRegistryDbFails(): void
    {
        $tmp = $_GET;
        $_GET['email'] = 'an-email';
        $_GET['password'] = 'a-password';
        $params = new Params();
        
        $session = new Session();
        
        $csrf = new Csrf($session, $params);
        
        $safer = new Safer();
        
        $template = new Template($safer, $csrf);
        
        $merger = new Merger();
        
        $user = $this->getMock(Assoc::class);
        $user->shouldReceive('get')->andReturnUsing(
            static function ($arg) {
                if ($arg === 'email') {
                    return 'an-email-2';
                }
                throw new RuntimeException('Invalid argument: ' . $arg);
            }
        );
        
        $crud = $this->getMock(Crud::class);
        $crud->shouldReceive('get')->andReturn($user);
        $crud->shouldReceive('add')->andReturnFalse();
        
        $validator = $this->getMock(Validator::class);
        $validator->shouldReceive('validateRegistry')->andReturn([]);
        
        $mailer = $this->getMock(Mailer::class);
        
        $config = new Config($template);
        
        $registy = new Registry(
            $template,
            $merger,
            $session,
            $crud, // @phpstan-ignore-line
            $params,
            $validator, // @phpstan-ignore-line
            $mailer, // @phpstan-ignore-line
            $config // @phpstan-ignore-line
        );
        $result = $registy->doRegistry();
        $this->assertStringContains('User is not saved', $result);
        
        $_GET = $tmp;
    }
    
    /**
     * Method testDoRegistryEmailFails
     *
     * @return void
     *
     * @suppress PhanUndeclaredClassMethod
     * @suppress PhanTypeMismatchArgument
     * @suppress PhanUnreferencedPublicMethod
     * @suppress PhanUnreferencedClosure
     */
    public function testDoRegistryEmailFails(): void
    {
        $tmp = $_GET;
        $_GET['email'] = 'an-email';
        $_GET['password'] = 'a-password';
        $params = new Params();
        
        $session = new Session();
        
        $csrf = new Csrf($session, $params);
        
        $safer = new Safer();
        
        $template = new Template($safer, $csrf);
        
        $merger = new Merger();
        
        $user = $this->getMock(Assoc::class);
        $user->shouldReceive('get')->andReturnUsing(
            static function ($arg) {
                if ($arg === 'email') {
                    return 'an-email-2';
                }
                throw new RuntimeException('Invalid argument: ' . $arg);
            }
        );
        
        $crud = $this->getMock(Crud::class);
        $crud->shouldReceive('get')->andReturn($user);
        $crud->shouldReceive('add')->andReturnTrue();
        
        $validator = $this->getMock(Validator::class);
        $validator->shouldReceive('validateRegistry')->andReturn([]);
        
        $mailer = $this->getMock(Mailer::class);
        $mailer->shouldReceive('send')->andReturnFalse();
        
        $config = new Config($template);
        
        $registy = new Registry(
            $template,
            $merger,
            $session,
            $crud, // @phpstan-ignore-line
            $params,
            $validator, // @phpstan-ignore-line
            $mailer, // @phpstan-ignore-line
            $config
        );
        $result = $registy->doRegistry();
        $this->assertStringContains('Activation email is not sent', $result);
        
        $_GET = $tmp;
    }
}
