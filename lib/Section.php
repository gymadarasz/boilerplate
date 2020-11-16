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
 * Section
 *
 * @category  PHP
 * @package   Madsoft\Library
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 */
class Section implements Assoc
{
    
    /**
     * Variable $data
     *
     * @var mixed[]
     */
    protected array $data = [];
    
    /**
     * Method create
     *
     * @param mixed[] $values values
     *
     * @return self
     */
    protected function create(array $values): self
    {
        $section = new self();
        $section->data = $values;
        return $section;
    }
    
    /**
     * Method get
     *
     * @param string $key     key
     * @param mixed  $default default
     *
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        if (!array_key_exists($key, $this->data)) {
            return $default;
        }
        if (is_array($this->data[$key])) {
            $this->data[$key] = $this->create($this->data[$key]);
        }
        return $this->data[$key];
    }

    /**
     * Method has
     *
     * @param string $key key
     *
     * @return bool
     */
    public function has(string $key): bool
    {
        return array_key_exists($key, $this->data);
    }

    /**
     * Method set
     *
     * @param string $key   key
     * @param mixed  $value value
     *
     * @return Assoc
     */
    public function set(string $key, $value): Assoc
    {
        $this->data[$key] = $value;
        return $this;
    }
}
