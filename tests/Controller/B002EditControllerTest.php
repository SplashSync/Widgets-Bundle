<?php

namespace Splash\Widgets\Tests\Controller;

use Symfony\Component\Process\Process;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Splash\Widgets\Tests\Traits\UrlCheckerTrait;

use Splash\Widgets\Services\ManagerService;
use Splash\Widgets\Tests\Services\SamplesFactoryService as SamplesFactory;

use Splash\Widgets\Entity\WidgetCache;

class B002EditControllerTest extends WebTestCase
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
     * @abstract    Check Widget is Forced Rendering
     * @dataProvider widgetDemoNamesProvider
     */    
    public function testEditModal($Service, $Type, $Options, $Parameters)
    {
        
        //====================================================================//
        // Build Route Parameters 
        $Params =   array(
            "Service"   =>  $Service,
            "Type"      =>  $Type,
        );
        
        //====================================================================//
        // Render Forced 
        $Crawler    =   $this->assertRouteWorks("splash_widgets_edit_widget",  $Params);
        
        //====================================================================//
        // Verify Form is Here
        $this->assertEquals(1 , $Crawler->filterXpath('//*[@name="splash_widgets_settings_form"]')->count() );
        $this->assertEquals(1 , $Crawler->filterXpath('//*[@id="splash_widgets_settings_form_rendering"]')->count() );
        $this->assertEquals(1 , $Crawler->filterXpath('//*[@id="splash_widgets_settings_form_rendering_Width"]')->count() );
        $this->assertEquals(1 , $Crawler->filterXpath('//*[@id="splash_widgets_settings_form_rendering_Color"]')->count() );
        $this->assertEquals(2 , $Crawler->filterXpath('//*[@name="splash_widgets_settings_form[rendering][Header]"]')->count() );
        $this->assertEquals(2 , $Crawler->filterXpath('//*[@name="splash_widgets_settings_form[rendering][Footer]"]')->count() );
        $this->assertEquals(2 , $Crawler->filterXpath('//*[@name="splash_widgets_settings_form[rendering][UseCache]"]')->count() );
        
        $this->assertEquals(1 , $Crawler->filterXpath('//*[@id="splash_widgets_settings_form_dates"]')->count() );
        $this->assertEquals(1 , $Crawler->filterXpath('//*[@id="splash_widgets_settings_form_dates_Dates"]')->count() );
        
    }   

    
    
    public function widgetDemoNamesProvider()
    {
        $this->client   =   self::createClient();
        
        //====================================================================//
        // Link to Widget Manager Service
        $this->Manager = static::$kernel->getContainer()->get('Splash.Widgets.Manager');

        //====================================================================//
        // Get Demo List
        $List   =   array();
        foreach ( $this->Manager->getList(ManagerService::DEMO_WIDGETS) as $Widget ) {
            $List[]     =   array(
                "Service"       =>  $Widget->getService(),
                "Type"          =>  $Widget->getType(),
                "Options"       =>  $this->Manager->getWidgetOptions($Widget->getService(), $Widget->getType()),
                "Parameters"    =>  $this->Manager->getWidgetParameters($Widget->getService(), $Widget->getType())
                    );
        }

        return $List;        
    }      
    
}
