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
use Madsoft\Library\Session;
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
    protected Invoker $invoker;
    protected Session $session;
     
    /**
     * Method __construct
     *
     * @param Invoker $invoker invoker
     * @param Session $session session
     */
    public function __construct(Invoker $invoker, Session $session)
    {
        $this->invoker = $invoker;
        $this->session = $session;
    }
    /**
     * Method testTalkbot
     *
     * @return void
     *
     * @suppress PhanUnreferencedPublicMethod
     */
    public function testTalkbot(): void
    {
        $talkbot = new Talkbot($this->invoker);
        $talkbot->getOutput([Account::ROUTES, Talkbot::ROUTES]);
        $this->assertTrue((bool)$talkbot);
    }
    
    /**
     * Method testMyScripts
     *
     * @return void
     *
     * @suppress PhanUnreferencedPublicMethod
     */
    public function testMyScripts(): void
    {
        $this->session->set('uid', 1);
        
        $this->canSeeCreate();
        $this->canSeeCreateWorks();
        $this->canSeeListWorks();
        
        $this->session->set('uid', 0);
    }
    
    /**
     * Method canSeeCreate
     *
     * @return void
     */
    protected function canSeeCreate(): void
    {
        $result = $this->get('q=my-scripts/create');
        $this->assertStringContains('My Scripts / Create', $result);
    }
    
    /**
     * Method canSeeCreateWorks
     *
     * @return void
     */
    protected function canSeeCreateWorks(): void
    {
        $result = $this->post(
            'q=my-scripts/create',
            [
                'csrf' => $this->session->get('csrf'),
                'name' => 'testscript',
            ]
        );
        
        $this->assertStringContains(
            htmlentities('Script "testscript" is created'),
            $result
        );
    }
    
    /**
     * Method canSeeListWorks
     *
     * @return void
     */
    protected function canSeeListWorks(): void
    {
        $result = $this->get('q=my-scripts/list');
        $this->assertStringContains('My Scripts', $result);
        $this->assertStringContains('testscript', $result);
    }
}
