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

/**
 * Merger
 *
 * @category  PHP
 * @package   Madsoft\Library
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 */
class Merger
{
    

    /**
     * Method merge
     *
     * @param mixed[] $array1 array1
     * @param mixed[] $array2 array2
     *
     * @return mixed[]
     */
    public function merge(array $array1, array $array2): array
    {
        $merged = $array1;

        foreach ($array2 as $key => & $value) {
            $merged[$key] = is_array($value)
                    && isset($merged[$key]) && is_array($merged[$key]) ?
                    $this->merge($merged[$key], $value) :
                    $merged[$key] = $value;
        }

        return $merged;
    }
}
