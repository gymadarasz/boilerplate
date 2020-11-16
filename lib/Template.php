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
 * Template
 *
 * @category  PHP
 * @package   Madsoft\Library
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 */
class Template
{
    //    public string $tplDir = __DIR__ . '/tpls/';
            
    const RESERVED_VARS = [
        'safer',
        'filename',
        'csrf',
        'csrfgen',
    ];
    
    public string $csrf;
    
    protected Safer $safer;
    protected Csrf $csrfgen;
    
    /**
     * Method __construct
     *
     * @param Safer $safer safer
     * @param Csrf  $csrf  csrf
     */
    public function __construct(Safer $safer, Csrf $csrf)
    {
        $this->safer = $safer;
        $this->csrfgen = $csrf;
    }
   
    /**
     * Method process
     *
     * @param string $filename filename
     * @param mixed  $data     data
     *
     * @return string
     * @throws RuntimeException
     */
    public function process(string $filename, $data = []): string
    {
        foreach ($this->safer->freez('htmlentities', $data) as $key => $value) {
            if (is_numeric($key)) {
                throw new RuntimeException(
                    "Variable name can not be number: '$key'"
                );
            }
            if (in_array($key, self::RESERVED_VARS)) {
                throw new RuntimeException(
                    "Variable name is reserved: '$key'"
                );
            }
            $this->$key = $value;
        }
        $this->csrf = $this->csrfgen->get();
        ob_start();
        $this->include($filename);
        $contents = (string)ob_get_contents();
        ob_end_clean();
        return $contents;
    }
    
    /**
     * Method include
     *
     * @param string $filename filename
     *
     * @return void
     */
    protected function include(string $filename): void
    {
        foreach ((array)$this as $key => $value) {
            $$key = $value;
        }
        include $filename;
    }
    
    /**
     * Method restrict
     *
     * @return void
     */
    public function restrict(): void
    {
    }
}
