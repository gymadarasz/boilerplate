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
 * RegexNotMatch
 *
 * @category  PHP
 * @package   Madsoft\Library\Validator\Rule
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 */
class RegexNotMatch implements Rule
{
    public string $pattern = '/.*/';
    
    /**
     * Method check
     *
     * @param string $value value
     *
     * @return bool
     */
    public function check(string $value): bool
    {
        return !preg_match($this->pattern, $value);
    }
}
