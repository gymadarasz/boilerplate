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

use Madsoft\Library\Invoker;

/**
 * Validator
 *
 * @category  PHP
 * @package   Madsoft\Library\Validator
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 */
class Validator
{
    protected Invoker $invoker;
    
    /**
     * Method __construct
     *
     * @param Invoker $invoker invoker
     */
    public function __construct(Invoker $invoker)
    {
        $this->invoker = $invoker;
    }
    
    /**
     * Method check
     *
     * @param string   $value value
     * @param string[] $rules rules
     *
     * @return bool
     */
    public function check(string $value, array $rules): bool
    {
        foreach ($rules as $class) {
            if (!$this->invoker->getInstance($class)->check($value)) {
                return false;
            }
        }
        return true;
    }
}
