<?php declare(strict_types = 1);

/**
 * PHP version 7.4
 *
 * @category  PHP
 * @package   Madsoft\Library\Ctrlr
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 */

namespace Madsoft\Library\Ctrlr;

use Exception;
use Madsoft\Library\Logger;

/**
 * Error
 *
 * @category  PHP
 * @package   Madsoft\Library\Ctrlr
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 */
class Error
{
    /**
     * Method error
     *
     * @return string
     */
    public function error(): string
    {
        return 'Error: Hoops! Something went wrong...';
    }
}
