<?php declare (strict_types = 1);

namespace Madsoft\Test;

use Madsoft\Library\Invoker;
use Madsoft\Library\Tester;

include __DIR__ . '/vendor/autoload.php';

$tester = (new Invoker())->getInstance(Tester::class);

$tester->getCoverage()->start([
    __DIR__ . "/vendor/",
    __DIR__ . "/lib/Coverage.php",
    __DIR__ . "/lib/Test.php",
    __DIR__ . "/lib/Tester.php",
    __DIR__ . "/tests/",
]);

array_shift($argv);
if (empty($argv)) {
    $tester->test();
} else {
    foreach ($argv as $arg) {
        $tester->runTestFile($arg, '');
    }
}

exit($tester->stat() ? 0 : 1);
