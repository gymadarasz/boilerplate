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
use Madsoft\Library\Messages;
use Madsoft\Library\Responder\TemplateResponder;

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
class TalkbotTemplateResponder extends TemplateResponder
{
    /**
     * Method __construct
     *
     * @param Messages        $messages messages
     * @param Merger          $merger   merger
     * @param TalkbotTemplate $template template
     */
    public function __construct(
        Messages $messages,
        Merger $merger,
        TalkbotTemplate $template
    ) {
        parent::__construct($messages, $merger, $template);
    }
}
