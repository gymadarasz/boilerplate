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
 * Config
 *
 * @category  PHP
 * @package   Madsoft\Library
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 */
class Config extends Section
{
    const ENV = 'test';
    const PATH = __DIR__ . '/config/';
    
    protected Section $section;
    protected Template $template;
    
    /**
     * Method __construct
     *
     * @param Section  $section  section
     * @param Template $template template
     */
    public function __construct(
        Section $section,
        Template $template
    ) {
        $this->section = $section;
        $this->template = $template;
        
        $cfg = $this->readConfig(self::PATH . 'config.ini');
        $ext = $this->readConfig(self::PATH . 'config.' . self::ENV . '.ini');
        $this->data = $this->merge($cfg, $ext);
    }
    
    /**
     * Method merge
     *
     * @param mixed[] $array1 array1
     * @param mixed[] $array2 array2
     *
     * @return mixed[]
     */
    protected function merge(array $array1, array $array2): array
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
    
    /**
     * Method readConfig
     *
     * @param string $filename filename
     *
     * @return mixed[]
     * @throws RuntimeException
     */
    protected function readConfig(string $filename): array
    {
        if (!file_exists($filename)) {
            throw new RuntimeException('Config file not found: ' . $filename);
        }
        $cfg = parse_ini_string(
            $this->template->process(
                $filename,
                ['dir' => __DIR__]
            ),
            true
        );
        
        if (false === $cfg) {
            throw new RuntimeException('Config error: ' . $filename);
        }
        
        return $cfg;
    }
}
