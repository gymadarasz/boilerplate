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
use RuntimeException;

/**
 * RegexMatch
 *
 * @category  PHP
 * @package   Madsoft\Library\Validator\Rule
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 */
class RegexMatch implements Rule
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
        $results = preg_match($this->pattern, $value);
        if (false === $results) {
            throw new RuntimeException('Regex matching error');
        }
        return (bool)$results;
    }
}
