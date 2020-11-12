<?php declare(strict_types = 1);

/**
 * PHP version 7.4
 *
 * @category  PHP
 * @package   Madsoft\Library\Validator
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 */

namespace Madsoft\Library\Validator;

/**
 * Email
 *
 * @category  PHP
 * @package   Madsoft\Library\Validator
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 */
class Email implements Validator
{
    
    /**
     * Method getErrors
     *
     * @param string $value  value
     * @param string $prefix prefix
     *
     * @return string[]
     */
    public function getErrors(string $value, string $prefix = ''): array
    {
        $errors = [];
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $errors[] = $prefix . 'Invalid email format';
        }
        return $errors;
    }
}
