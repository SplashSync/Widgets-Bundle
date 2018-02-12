<?php

namespace Splash\Widgets\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Splash\Widgets\Tests\Traits\UrlCheckerTrait;

class D001DemoControllerTest extends WebTestCase
{
    use UrlCheckerTrait;
    
    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        $this->client   =   self::createClient();
    }        

    /**
     * @abstract    Check Manager Class
     * @dataProvider demoRoutesProvider
     */    
    public function testDemoPages($Route)
    {
        $this->assertRouteWorks($Route);
    } 
    
    
    public function demoRoutesProvider()
    {
        return array(
            array("splash_widgets_demo_home"),
            array("splash_widgets_demo_single_blocks"),
            array("splash_widgets_demo_collection"),
            array("splash_widgets_demo_collection_edit"),
            array("splash_widgets_demo_list"),
            array("splash_widgets_demo_single_forced"),
            array("splash_widgets_demo_single_delayed"),
            array("splash_widgets_demo_single_edit"),
            array("splash_widgets_demo_blocks"),
        );        
    }  
    
}
