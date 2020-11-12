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
 * Password
 *
 * @category  PHP
 * @package   Madsoft\Library\Validator
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 */
class Password extends Required implements Validator
{
    public int $minLength = 8;
    public bool $hasNumber = true;
    public bool $hasSpecChar = true;
    
    protected Required $required;
    
    /**
     * Method __construct
     *
     * @param Required $required required
     */
    public function __construct(Required $required)
    {
        $this->required = $required;
    }
    
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
        $errors = $this->required->getErrors($value, $prefix . 'Invalid password: ');
        if (strlen($value) < $this->minLength) {
            $errors[] = $prefix . 'Password too short, minimum lenght is '
                    . $this->minLength . ' characters.';
        }
        if ($this->hasNumber && preg_match('/^\D*$/', $value)) {
            $errors[] = $prefix . 'Password should contains one number at least.';
        }
        if ($this->hasSpecChar && !preg_match('/[\W\D]/', $value)) {
            $errors[] = $prefix .
                    'Password should contains one special charater at least.';
        }
        return $errors;
    }
}
