<?php declare(strict_types = 1);

/**
 * PHP version 7.4
 *
 * @category  PHP
 * @package   Madsoft\Library\Test
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 */

namespace Madsoft\Library\Test;

use Madsoft\Library\Csrf;
use Madsoft\Library\Invoker;
use Madsoft\Library\Safer;
use Madsoft\Library\Template;
use Madsoft\Library\Test;
use RuntimeException;

/**
 * TemplateTest
 *
 * @category  PHP
 * @package   Madsoft\Library\Test
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 */
class TemplateTest extends Test
{
    /**
     * Method testTemplate
     *
     * @param Invoker $invoker invoker
     *
     * @return void
     */
    public function testTemplate(Invoker $invoker): void
    {
        $template = new Template(
            new Safer(),
            $invoker->getInstance(Csrf::class)
        );
        $msg = '';
        
        try {
            $template->process(
                __DIR__ . '/Mock/test.phtml',
                ['foo']
            );
        } catch (RuntimeException $exception) {
            $msg = $exception->getMessage();
        }
        $this->assertEquals("Variable name can not be number: '0'", $msg);
        
        try {
            $template->process(
                __DIR__ . '/Mock/test.phtml',
                ['safer' => 'never!']
            );
        } catch (RuntimeException $exception) {
            $msg = $exception->getMessage();
        }
        $this->assertEquals("Variable name is reserved: 'safer'", $msg);
        
        $results = $template->process(
            __DIR__ . '/Mock/test.phtml',
            ['data1' => 'foo']
        );
        $this->assertEquals('Hello Template foo!', $results);
        
        $template->restrict();
    }
}
