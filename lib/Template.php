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
    const TPL_PATH = __DIR__ . '/tpls/';
    const TPL_PATH_EXT = __DIR__ . '/tpls/';
    
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
            if (in_array($key, self::RESERVED_VARS, true)) {
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
     *
     * @suppress PhanUnusedVariable
     * @suppress PhanPluginDollarDollar
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
    
    /**
     * Method getTemplateFile
     *
     * @param string $tplfile tplfile
     *
     * @return string
     * @throws RuntimeException
     */
    public function getTemplateFile(string $tplfile): string
    {
        $fullpath = $this::TPL_PATH_EXT . $tplfile;
        if (!file_exists($fullpath)) {
            $fullpath = $this::TPL_PATH . $tplfile;
        }
        if (!file_exists($fullpath)) {
            throw new RuntimeException(
                'Template file not found: ' . $this::TPL_PATH_EXT . $tplfile .
                ' nor: ' . $fullpath
            );
        }
        return $fullpath;
    }
}
