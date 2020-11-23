<?php declare(strict_types = 1);

/**
 * PHP version 7.4
 *
 * @category  PHP
 * @package   Madsoft\Talkbot\Test
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 */

namespace Madsoft\Talkbot\Test;

use Madsoft\Library\Account\Account;
use Madsoft\Library\Invoker;
use Madsoft\Library\Test;
use Madsoft\Talkbot\Talkbot;

/**
 * TalkbotTest
 *
 * @category  PHP
 * @package   Madsoft\Talkbot\Test
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 *
 * @suppress PhanUnreferencedClass
 */
class TalkbotTest extends Test
{
    /**
     * Method testTalkbot
     *
     * @param Invoker $invoker invoker
     *
     * @return void
     *
     * @suppressWarnings(PHPMD.Superglobals)
     *
     * @suppress PhanUnreferencedPublicMethod
     */
    public function testTalkbot(Invoker $invoker): void
    {
        $this->pushGlobals();
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $talkbot = new Talkbot($invoker);
        $talkbot->getOutput([Account::ROUTES, Talkbot::ROUTES]);
        $this->assertTrue((bool)$talkbot);
        $this->popGlobals();
    }
}
