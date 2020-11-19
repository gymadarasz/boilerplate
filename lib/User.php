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
 * User
 *
 * @category  PHP
 * @package   Madsoft\Library
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 */
class User
{
    const SESSION_KEY = 'User';

    protected int $uid = 0;
    protected string $email = '';
    
    protected Session $session;
    
    /**
     * Method __construct
     *
     * @param Session $session session
     */
    public function __construct(Session $session)
    {
        $this->session = $session;
        //        $this->session->destroy();
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
     * Method setUid
     *
     * @param int $uid uid
     *
     * @return void
     */
    public function setUid(int $uid): void
    {
        $this->uid = $uid;
    }
    
    /**
     * Method setEmail
     *
     * @param string $email email
     *
     * @return void
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }
    
    /**
     * Method restore
     *
     * @return bool
     */
    protected function restore(): bool
    {
        $that = $this->session->get(self::SESSION_KEY, null);
        if (null !== $that) {
            foreach ($that as $key => $value) {
                $this->$key = $value;
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
        $this->session->set(self::SESSION_KEY, $this);
        return $this;
    }
    
    /**
     * Method isVisitor
     *
     * @return bool
     */
    public function isVisitor(): bool
    {
        return !$this->uid;
    }
    
    /**
     * Method logout
     *
     * @return self
     */
    public function logout(): self
    {
        $this->uid = 0;
        $this->email = '';
        return $this->store();
    }
}
