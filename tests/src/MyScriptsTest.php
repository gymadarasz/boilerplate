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

use Madsoft\Library\Session;
use Madsoft\Library\Tester\Test;

/**
 * MyScriptsTest
 *
 * @category  PHP
 * @package   Madsoft\Talkbot\Test
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 */
class MyScriptsTest extends Test
{
    protected Session $session;
     
    /**
     * Method __construct
     *
     * @param Session $session session
     */
    public function __construct(Session $session)
    {
        $this->session = $session;
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
        
        $this->session->set('uid', 2);
        
        $this->canSeeUser2Create();
        $this->canSeeUser2CreateWorks();
        $this->canSeeUser2ListWorks();
        
        $this->session->set('uid', 1);
        
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
        $this->assertStringNotContains('test2script', $result);
    }
    
    /**
     * Method canSeeUser2Create
     *
     * @return void
     */
    protected function canSeeUser2Create(): void
    {
        $result = $this->get('q=my-scripts/create');
        $this->assertStringContains('My Scripts / Create', $result);
    }
    
    /**
     * Method canSeeUser2CreateWorks
     *
     * @return void
     */
    protected function canSeeUser2CreateWorks(): void
    {
        $result = $this->post(
            'q=my-scripts/create',
            [
                'csrf' => $this->session->get('csrf'),
                'name' => 'test2script',
            ]
        );
        
        $this->assertStringContains(
            htmlentities('Script "test2script" is created'),
            $result
        );
    }
    
    /**
     * Method canSeeUser2ListWorks
     *
     * @return void
     */
    protected function canSeeUser2ListWorks(): void
    {
        $result = $this->get('q=my-scripts/list');
        $this->assertStringContains('My Scripts', $result);
        $this->assertStringContains('test2script', $result);
        $this->assertStringNotContains('testscript', $result);
    }
}
