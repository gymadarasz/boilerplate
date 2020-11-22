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

use Madsoft\Library\Merger;
use Madsoft\Library\Responder;

/**
 * TalkbotResponder
 *
 * @category  PHP
 * @package   Madsoft\Talkbot
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 */
class TalkbotResponder extends Responder
{
    /**
     * Method __construct
     *
     * @param TalkbotTemplate $template template
     * @param Merger          $merger   merger
     */
    public function __construct(
        TalkbotTemplate $template,
        Merger $merger
    ) {
        parent::__construct($template, $merger);
    }
}
