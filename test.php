<?php declare (strict_types = 1);

namespace Madsoft\Test;

use Madsoft\Library\Invoker;
use Madsoft\Library\Test\LibraryTestCleaner;
use Madsoft\Library\Tester;
use Madsoft\Talkbot\Test\TalkbotTestCleaner;
use RuntimeException;

include __DIR__ . '/vendor/autoload.php';

if (php_sapi_name() !== 'cli') {
    throw new RuntimeException('Test can run only from command line.');
}

$tester = (new Invoker())->getInstance(Tester::class)->setCleaners([
    TalkbotTestCleaner::class,
    LibraryTestCleaner::class,
]);
        
$tester->cleanUp();

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

$tester->cleanUp();

exit($tester->stat() ? 0 : 1);
