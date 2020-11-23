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

use Madsoft\Library\Test\LibraryTestCleaner;

/**
 * TalkbotTestCleaner
 *
 * @category  PHP
 * @package   Madsoft\Talkbot\Test
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 */
class TalkbotTestCleaner extends LibraryTestCleaner
{
        
    /**
     * Method cleanUp
     *
     * @return void
     */
    public function cleanUp(): void
    {
        $this->crud->del('script', [], 0);
        $this->deleteMails();
    }
}
