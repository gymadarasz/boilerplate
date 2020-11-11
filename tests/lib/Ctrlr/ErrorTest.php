<?php declare(strict_types = 1);

/**
 * PHP version 7.4
 *
 * @category  PHP
 * @package   Madsoft\Library\Test\Ctrlr
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 */

namespace Madsoft\Library\Test\Ctrlr;

use Madsoft\Library\RequestTest;

/**
 * IndexTest
 *
 * @category  PHP
 * @package   Madsoft\Library\Test\Ctrlr
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 */
class ErrorTest extends RequestTest
{
    /**
     * Method testError
     *
     * @return void
     */
    public function testError(): void
    {
        $response = $this->get('q=non-exists-request');
        $this->assertStringContains('Error', $response);
        $this->assertStringContains('Something went wrong', $response);
    }
}
