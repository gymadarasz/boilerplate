<?php declare(strict_types = 1);

/**
 * PHP version 7.4
 *
 * @category  PHP
 * @package   Madsoft\Talkbot
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 */

namespace Madsoft\Talkbot;

use Madsoft\Library\App;

/**
 * Talkbot
 *
 * @category  PHP
 * @package   Madsoft\Talkbot
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 */
class Talkbot extends App
{
    const ROUTES = [
        'protected' => [
            'GET' => [
                'my-chats' => [Talkbot::class, 'myChats'],
            ],
        ],
    ];
    
    /**
     * Method myChats
     *
     * @return string
     *
     * @suppress PhanUnreferencedPublicMethod
     */
    public function myChats(): string
    {
        return 'unimplementd';
    }
}
