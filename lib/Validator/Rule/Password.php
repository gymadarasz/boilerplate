<?php declare(strict_types = 1);

/**
 * PHP version 7.4
 *
 * @category  PHP
 * @package   Madsoft\Library\Validator\Rule
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 */

namespace Madsoft\Library\Validator\Rule;

use Madsoft\Library\Validator\Rule;

/**
 * Password
 *
 * @category  PHP
 * @package   Madsoft\Library\Validator\Rule
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 */
class Password implements Rule
{
    public Mandatory $mandatory;
    public MinLength $minLength;
    public HasLetter $hasLetter;
    public HasLower $hasLower;
    public HasUpper $hasUpper;
    public HasNumber $hasNumber;
    public HasSpecChar $hasSpecChar;
    
    /**
     * Method __construct
     *
     * @param Mandatory   $mandatory   mandatory
     * @param MinLength   $minLength   minLength
     * @param HasLetter   $hasLetter   hasLetter
     * @param HasLower    $hasLower    hasLower
     * @param HasUpper    $hasUpper    hasUpper
     * @param HasNumber   $hasNumber   hasNumber
     * @param HasSpecChar $hasSpecChar hasSpecChar
     */
    public function __construct(
        Mandatory $mandatory,
        MinLength $minLength,
        HasLetter $hasLetter,
        HasLower $hasLower,
        HasUpper $hasUpper,
        HasNumber $hasNumber,
        HasSpecChar $hasSpecChar
    ) {
        $this->mandatory = $mandatory;
        $this->minLength = $minLength;
        $this->hasLetter = $hasLetter;
        $this->hasLower  =$hasLower;
        $this->hasUpper = $hasUpper;
        $this->hasNumber  =$hasNumber;
        $this->hasSpecChar = $hasSpecChar;
        
        $this->minLength->min = 8;
    }
    
    /**
     * Method check
     *
     * @param string $value value
     *
     * @return bool
     */
    public function check(string $value): bool
    {
        if (!$this->mandatory->check($value)) {
            return false;
        }
        if (!$this->minLength->check($value)) {
            return false;
        }
        if (!$this->hasLetter->check($value)) {
            return false;
        }
        if (!$this->hasLower->check($value)) {
            return false;
        }
        if (!$this->hasUpper->check($value)) {
            return false;
        }
        if (!$this->hasNumber->check($value)) {
            return false;
        }
        if (!$this->hasSpecChar->check($value)) {
            return false;
        }
        return true;
    }
}
