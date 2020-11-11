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

use Exception;

/**
 * Test
 *
 * @category  PHP
 * @package   Madsoft\Library
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 */
abstract class Test
{
    /**
     * Variable $failInfos
     *
     * @var string[]
     */
    protected array $failInfos = [];
    
    protected int $asserts = 0;
    protected int $success = 0;
    protected int $fails = 0;
    
    /**
     * Method showTick
     *
     * @return void
     */
    protected function showTick(): void
    {
        echo '.';
    }
    
    /**
     * Method showFail
     *
     * @return void
     */
    protected function showFail(): void
    {
        echo 'X';
    }
    
    /**
     * Method varDump
     *
     * @param mixed $var var
     *
     * @return string
     */
    protected function varDump($var): string
    {
        $type = gettype($var);
        return "($type) " . print_r($var, true);
    }
    
    /**
     * Method storeFail
     *
     * @param mixed $expected expected
     * @param mixed $result   result
     * @param mixed $message  message
     *
     * @return void
     */
    protected function storeFail($expected, $result, $message): void
    {
        try {
            throw new Exception();
        } catch (Exception $exception) {
            $trace = $exception->getTraceAsString();
        }
        $this->failInfos[] = "$message\nExpected: "
                . $this->varDump($expected) . "\nResult: "
                . $this->varDump($result) . "\nTrace:\n"
                . $trace;
    }
    
    /**
     * Method assertTrue
     *
     * @param bool   $result  result
     * @param string $message message
     * @param mixed  $origExp origExp
     * @param mixed  $origRes origRes
     *
     * @return void
     */
    public function assertTrue(
        bool $result,
        string $message = 'Assert true failed.',
        $origExp = null,
        $origRes = null
    ): void {
        $this->asserts++;
        if ($result) {
            $this->success++;
            $this->showTick();
            return;
        }
        $this->fails++;
        $this->showFail();
        $this->storeFail($origExp, $origRes, $message);
    }
    
    /**
     * Method assertFalse
     *
     * @param bool   $result  result
     * @param string $message message
     *
     * @return void
     */
    public function assertFalse(
        bool $result,
        string $message = 'Assert false failed.'
    ): void {
        $this->assertTrue($result, $message);
    }
    
    /**
     * Method assertEquals
     *
     * @param mixed $expected expected
     * @param mixed $result   result
     * @param mixed $message  message
     *
     * @return void
     */
    public function assertEquals(
        $expected,
        $result,
        $message = 'Assert equals failed.'
    ): void {
        $this->assertTrue($expected === $result, $message, $expected, $result);
    }
    
    /**
     * Method assertEquals
     *
     * @param mixed $expected expected
     * @param mixed $result   result
     * @param mixed $message  message
     *
     * @return void
     */
    public function assertNotEquals(
        $expected,
        $result,
        $message = 'Assert not equals failed.'
    ): void {
        $this->assertTrue($expected !== $result, $message, $expected, $result);
    }
}
