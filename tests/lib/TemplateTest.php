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
     * @return void
     */
    public function testTemplate(): void
    {
        $template = new Template(new Safer);
        $template->tplDir = __DIR__ . '/Mock/';
        $msg = '';
        
        try {
            $template->process('test.phtml', ['foo']);
        } catch (RuntimeException $exception) {
            $msg = $exception->getMessage();
        }
        $this->assertEquals("Variable name can not be number: '0'", $msg);
        
        try {
            $template->process('test.phtml', ['safer' => 'never!']);
        } catch (RuntimeException $exception) {
            $msg = $exception->getMessage();
        }
        $this->assertEquals("Variable name is reserved: 'safer'", $msg);
        
        $results = $template->process('test.phtml', ['data1' => 'foo']);
        $this->assertEquals('Hello Template foo!', $results);
        
        $template->restrict();
    }
}
