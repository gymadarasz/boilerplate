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
 */
class TalkbotTest extends Test
{
    /**
     * Method testTalkbot
     *
     * @return void
     */
    public function testTalkbot(): void
    {
        $talkbot = new Talkbot();
        $this->assertTrue((bool)$talkbot);
    }
}
