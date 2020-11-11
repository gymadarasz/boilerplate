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

use RuntimeException;

/**
 * RequestTest
 *
 * @category  PHP
 * @package   Madsoft\Library
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 */
class RequestTest extends Test
{
    /**
     * Method get
     *
     * @param string $params params
     *
     * @return string
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    protected function get(string $params = ''): string
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        parse_str($params, $_GET);
        ob_start();
        include __DIR__ . '/../index.php';
        $contents = ob_get_contents();
        ob_end_clean();
        if (false === $contents) {
            throw new RuntimeException("Output buffering isn't active");
        }
        return $contents;
    }
}
