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
class IndexTest extends RequestTest
{
    /**
     * Method testIndex
     *
     * @return void
     */
    public function testIndex(): void
    {
        $response = $this->get();
        $this->assertStringContains('Index', $response);
    }
}
