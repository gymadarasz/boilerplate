<?php declare(strict_types = 1);

/**
 * PHP version 7.4
 *
 * @category  PHP
 * @package   Madsoft\Library\Test\Mock
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 */

namespace Madsoft\Library\Test\Mock;

/**
 * NotInjectable
 *
 * @category  PHP
 * @package   Madsoft\Library\Test\Mock
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 */
class NotInjectable
{
    protected int $param;

    /**
     * Method __construct
     *
     * @param int $param param
     */
    public function __construct(int $param = 100)
    {
        $this->param = $param;
    }
    
    /**
     * Method param
     *
     * @param int|null $param param
     *
     * @return int
     */
    public function param(?int $param): int
    {
        if (null !== $param) {
            $this->param = $param;
        }
        return $this->param;
    }
}
