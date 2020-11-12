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

use Madsoft\Library\Invoker;
use Madsoft\Library\Logger;
use Madsoft\Library\RequestTest;
use function count;

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
    protected Logger $logger;
    protected Invoker $invoker;
    
    /**
     * Method __construct
     *
     * @param Logger  $logger  logger
     * @param Invoker $invoker invoker
     */
    public function __construct(Logger $logger, Invoker $invoker)
    {
        $this->logger = $logger;
        $this->invoker = $invoker;
    }
    
    /**
     * Method testError
     *
     * @return void
     */
    public function testError(): void
    {
        $this->logger->setCollect(true);
        $response = $this->get('q=non-exists-request');
        $collection = $this->logger->setCollect(false)->getCollection();
        $this->assertEquals(1, count($collection));
        $this->assertEquals('???', $collection[0]);
        $this->assertStringContains('Error', $response);
        $this->assertStringContains('Something went wrong', $response);
    }
}
