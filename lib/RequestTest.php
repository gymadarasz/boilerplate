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
 *
 * @SuppressWarnings(PHPMD.Superglobals)
 */
class RequestTest extends Test
{
    /**
     * Method get
     *
     * @param string $params params
     *
     * @return string
     * @throws RuntimeException
     */
    protected function get(string $params = ''): string
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        parse_str($params, $_GET);
        $_REQUEST = $_GET;
        unset($_POST);
        return $this->getContents();
    }
    
    /**
     * Method post
     *
     * @param string  $params params
     * @param mixed[] $data   data
     *
     * @return string
     * @throws RuntimeException
     */
    protected function post(string $params = '', array $data = []): string
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        parse_str($params, $_GET);
        $_REQUEST = $_GET;
        $_POST = $data;
        $_REQUEST = array_merge($_REQUEST, $_POST);
        return $this->getContents();
    }
    
    /**
     * Method getContents
     *
     * @return string
     * @throws RuntimeException
     */
    protected function getContents(): string
    {
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
