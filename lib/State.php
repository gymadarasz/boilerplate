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

use RuntimeException;

/**
 * State
 *
 * @category  PHP
 * @package   Madsoft\Library
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 */
class State
{
    protected string $key;
    protected Session $session;
    
    /**
     * Method __construct
     *
     * @param Session $session session
     */
    public function __construct(Session $session)
    {
        $this->session = $session;
        $this->key = get_class($this);
        $this->restore();
    }
    
    /**
     * Method __destruct
     */
    public function __destruct()
    {
        $this->store();
    }
    
    /**
     * Method restore
     *
     * @return bool
     */
    protected function restore(): bool
    {
        $that = $this->session->get($this->key, null);
        if (null !== $that) {
            foreach ((array)$that as $key => $value) {
                $this->$$key = $value;
            }
            return true;
        }
        return false;
    }
    
    /**
     * Method store
     *
     * @return self
     */
    protected function store(): self
    {
        $this->session->set($this->key, $this);
        return $this;
    }
}
