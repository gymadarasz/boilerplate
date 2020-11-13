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
use RuntimeException;

/**
 * Tester
 *
 * @category  PHP
 * @package   Madsoft\Library
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 */
class Tester extends Test
{
    const TESTS_PATH = __DIR__ . '/../tests';
    
    protected Folders $folders;
    protected Logger $logger;
    protected Invoker $invoker;
    protected Coverage $coverage;

    /**
     * Method __construct
     *
     * @param Folders  $folders  folders
     * @param Logger   $logger   logger
     * @param Invoker  $invoker  invoker
     * @param Coverage $coverage coverage
     */
    public function __construct(
        Folders $folders,
        Logger $logger,
        Invoker $invoker,
        Coverage $coverage
    ) {
        $this->folders = $folders;
        $this->logger = $logger;
        $this->invoker = $invoker;
        $this->coverage = $coverage;
    }
    
    /**
     * Method getCoverage
     *
     * @return Coverage
     */
    public function getCoverage(): Coverage
    {
        return $this->coverage;
    }
    
    /**
     * Method test
     *
     * @param string $path path
     *
     * @return void
     */
    public function test($path = self::TESTS_PATH): void
    {
        $files = $this->folders->getFilesRecursive($path);
        foreach ($files as $file) {
            $matches = [];
            if (preg_match('/^(.+Test).php$/', $file->getFilename(), $matches)) {
                $class = $matches[1];
                
                $fullname = $file->getPath() . '/' . $file->getFilename();
                $namespace = $this->getPhpNamespace($fullname);
                
                $fullclass = "$namespace\\$class";
                
                include_once $fullname;
                $this->run($fullclass);
            }
        }
    }
    
    /**
     * Method getPhpNamespace
     *
     * @param string $fullname fullname
     *
     * @return string
     * @throws RuntimeException
     */
    protected function getPhpNamespace(string $fullname): string
    {
        $contents = file_get_contents($fullname);
        if (false === $contents) {
            throw new RuntimeException(
                'Unable to read test file: ' . $fullname
            );
        }
        $matches = [];
        if (preg_match('/namespace\s+(.+);/', $contents, $matches)) {
            return $matches[1];
        }
        return '';
    }
    
    /**
     * Method run
     *
     * @param string $class class
     *
     * @return void
     * @throws RuntimeException
     */
    protected function run(string $class): void
    {
        $methods = get_class_methods($class);
        $test = $this->invoker->getInstance($class);
        foreach ($methods as $method) {
            if (preg_match('/^test/', $method)) {
                try {
                    $this->invoker->invoke([$class, $method]);
                } catch (Exception $exception) {
                    $this->assertFalse(
                        true,
                        "Tests should not throws exception but it's happened at "
                            . "$class::$method(), exception details:\n"
                            . (new Logger())->exceptionToString($exception)
                    );
                }
                if (!$test->asserts) {
                    throw new RuntimeException(
                        "$class::$method() has not any assertation."
                    );
                }
                $this->failInfos = array_merge($test->failInfos, $this->failInfos);
                $this->asserts += $test->asserts;
                $this->success += $test->success;
                $this->fails += $test->fails;
            }
        }
        $this->invoker->free($class);
    }
    
    /**
     * Method stat
     *
     * @param string $coverageOutput    coverageOutput
     * @param float  $coverageThreshold coverageThreshold
     *
     * @return bool
     */
    public function stat(
        string $coverageOutput = __DIR__ . '/../coverage/coverage.html',
        float $coverageThreshold = 100.0
    ): bool {
        $coverageData = [];
        $coveragePassed = true;
        if ($this->coverage->isStarted()) {
            $coverageData = $this->coverage->getCoverageData();
            $coveragePercentage = $this->coverage->getPercentage($coverageData);
            $coveragePassed = $coverageThreshold <= $coveragePercentage;
            echo "\nCode coverage:\t$coveragePercentage %";
        }
        echo "\nAsserts:\t{$this->asserts}";
        echo "\nSuccess:\t{$this->success}";
        if ($this->fails) {
            echo "\nFails:\t{$this->fails}";
            echo "\n\n" . implode("\n\n", $this->failInfos);
            echo "\n";
            return false;
        }
        if (!$coveragePassed) {
            echo "\nCoverage thrashold ($coverageThreshold %) failed.";
            echo "\nGenerate coverage info to '$coverageOutput'...";
            $output = $this->coverage->generateCoverageInfo($coverageData);
            $writeOk = false === file_put_contents($coverageOutput, $output);
            if ($writeOk) {
                echo '[ERR]';
            }
            if (!$writeOk) {
                echo "[OK]";
            }
            echo "\n";
            return false;
        }
        echo "\n";
        return true;
    }
}
