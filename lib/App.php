<?php declare(strict_types = 1);

/**
 * PHP version 7.4
 *
 * @category  PHP
 * @package   Madsoft\Library
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 */

namespace Madsoft\Library;

use Madsoft\Library\Account\Account;

/**
 * App
 *
 * @category  PHP
 * @package   Madsoft\Library
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 */
class App
{
    /**
     * Method getOutput
     *
     * @param Invoker $invoker invoker
     *
     * @return string
     */
    public function getOutput(Invoker $invoker): string
    {
        $output = $invoker
            ->getInstance(Invoker::class)
            ->getInstance(Request::class)
            ->setRoutes(Account::ROUTES)
            ->setError([Error::class, 'error'])
            ->process();
        $invoker->free();
        return $output;
    }
}
