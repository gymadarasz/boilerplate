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

use Madsoft\Library\Account\AccountActivateTemplateResponder;
use Madsoft\Library\Account\AccountActivateArrayResponder;
use Madsoft\Library\Account\AccountValidator;
use Madsoft\Library\Crud;
use Madsoft\Library\Csrf;
use Madsoft\Library\Merger;
use Madsoft\Library\Messages;
use Madsoft\Library\Params;
use Madsoft\Library\Row;
use Madsoft\Library\Safer;
use Madsoft\Library\Session;
use Madsoft\Library\Template;
use Madsoft\Library\Test;
use RuntimeException;

/**
 * ActivateTest
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
class ActivateTest extends Test
{
    
    /**
     * Method testDoActivateDbFails
     *
     * @return void
     *
     * @suppress PhanUndeclaredClassMethod
     * @suppress PhanTypeMismatchArgument
     * @suppress PhanUnreferencedPublicMethod
     * @suppress PhanUnreferencedClosure
     */
    public function testDoActivateDbFails(): void
    {
        $user = $this->getMock(Row::class);
        $user->shouldReceive('get')->andReturnUsing(
            static function ($arg) {
                if ($arg === 'id') {
                    return 123;
                }
                if ($arg === 'active') {
                    return 0;
                }
                throw new RuntimeException('Invalid argument: ' . $arg);
            }
        );
        
        $crud = $this->getMock(Crud::class);
        $crud->shouldReceive('get')->andReturn($user);
        $crud->shouldReceive('set')->andReturnFalse();
        
        $validator = $this->getMock(AccountValidator::class);
        $validator->shouldReceive('validateActivate')->andReturn([]);
        
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_GET['token'] = 'a-token';
        $params = new Params();
        
        $session = new Session();
        
        $csrf = new Csrf($session, $params);
        
        $safer = new Safer();
        
        $template = new Template($safer, $csrf);
        
        $merger = new Merger();
        
        $messages = new Messages();
        
        $arrayResponder = new AccountActivateArrayResponder(
            $messages,
            $merger,
            $session,
            $crud, // @phpstan-ignore-line
            $params,
            // @phpstan-ignore-next-line
            $validator
        );
        
        $activate = new AccountActivateTemplateResponder(
            $messages,
            $merger,
            $template
        );
        $result = $activate->getActivateResponse($arrayResponder);
        $this->assertStringContains('User activation failed', $result);
    }
}
