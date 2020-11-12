<?php declare (strict_types = 1);

namespace Madsoft\Test;

use Madsoft\Library\Invoker;
use Madsoft\Library\Test\Ctrlr\AuthTest;
use Madsoft\Library\Test\Ctrlr\ErrorTest;
use Madsoft\Library\Test\Ctrlr\IndexTest;
use Madsoft\Library\Test\InvokerTest;
use Madsoft\Library\Test\SaferTest;
use Madsoft\Library\Test\TemplateTest;
use Madsoft\Library\Test\TesterTest;
use Madsoft\Library\Test\TestTest;
use Madsoft\Library\Tester;
use Madsoft\Talkbot\Test\TalkbotTest;

include __DIR__ . '/vendor/autoload.php';

$tester = (new Invoker())->getInstance(Tester::class);

$tester->getCoverage()->start([
    __DIR__ . "/vendor/",
    __DIR__ . "/lib/Coverage.php",
    __DIR__ . "/lib/Test.php",
    __DIR__ . "/lib/Tester.php",
    __DIR__ . "/tests/lib/Mock/",
]);

// Library tests
$tester->run(InvokerTest::class);
$tester->run(SaferTest::class);
$tester->run(TemplateTest::class);
$tester->run(TestTest::class);
$tester->run(TesterTest::class);

$tester->run(IndexTest::class);
$tester->run(ErrorTest::class);
$tester->run(AuthTest::class);

// Talkbot tests
$tester->run(TalkbotTest::class);

exit($tester->stat() ? 0 : 1);
