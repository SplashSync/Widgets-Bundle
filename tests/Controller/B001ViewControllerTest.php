<?php

namespace Splash\Widgets\Tests\Controller;

use Symfony\Component\Process\Process;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Splash\Widgets\Tests\Traits\UrlCheckerTrait;

use Splash\Widgets\Services\ManagerService;
use Splash\Widgets\Tests\Services\SamplesFactoryService as SamplesFactory;

use Splash\Widgets\Entity\WidgetCache;

class B001ViewControllerTest extends WebTestCase
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
     * @abstract    Check Widget is Rendered Even if Errors
     */    
    public function testViewErrors()
    {
        //====================================================================//
        // Wrong Service Name 
        $Params =   array(
            "Service"   =>  SamplesFactory::SERVICE . ".Wrong",
            "Type"      =>  "Test",
        );
        $this->assertViewFailed($Params);
        
        //====================================================================//
        // Wrong Widget Type 
        $Params =   array(
            "Service"   =>  SamplesFactory::SERVICE,
            "Type"      =>  "Test" . ".Wrong",
        );
        $this->assertViewFailed($Params);
    }     
    
    
    /**
     * @abstract    Check Widget is Forced Rendering
     * @dataProvider widgetDemoNamesProvider
     */    
    public function testViewForced($Service, $Type, $Options, $Parameters)
    {
        
        //====================================================================//
        // Build Route Parameters 
        $Params =   array(
            "Service"   =>  $Service,
            "Type"      =>  $Type,
            "Options"   =>  json_encode($Options),
            "Parameters"=>  json_encode($Parameters),
        );
        
        //====================================================================//
        // Render Forced 
        $Crawler    =   $this->assertRouteWorks("splash_widgets_test_view_forced",  $Params);
        
        //====================================================================//
        // Verify Right Widget is Here
        $Xpath  =   '//*[@id="' . $Type . '-' . WidgetCache::buildDiscriminator($Options, $Parameters) . '"]';
        $this->assertEquals(1 , $Crawler->filterXpath($Xpath)->count() );
        
    }   

    /**
     * @abstract    Check Widget is Delayed Rendering
     * @dataProvider widgetDemoNamesProvider
     */    
    public function testViewDelayed($Service, $Type, $Options, $Parameters)
    {
        
        //====================================================================//
        // Build Route Parameters 
        $Params =   array(
            "Service"   =>  $Service,
            "Type"      =>  $Type,
            "Options"   =>  json_encode($Options),
            "Parameters"=>  json_encode($Parameters),
        );
        
        //====================================================================//
        // Render Forced 
        $Crawler    =   $this->assertRouteWorks("splash_widgets_test_view_delayed",  $Params);
        
        //====================================================================//
        // Verify Right Widget is Here
        $Xpath  =   '//*[@id="' . $Type . '-' . WidgetCache::buildDiscriminator($Options, $Parameters) . '"]';
        $this->assertEquals(1 , $Crawler->filterXpath($Xpath)->count() );
        
    }   
    
    /**
     * @abstract    Check Widget is Delayed Rendering
     * @dataProvider widgetDemoNamesProvider
     */    
    public function testViewAjax($Service, $Type, $Options, $Parameters)
    {
        
        //====================================================================//
        // Build Route Parameters 
        $Params =   array(
            "Service"   =>  $Service,
            "Type"      =>  $Type,
            "Options"   =>  json_encode($Options),
            "Parameters"=>  json_encode($Parameters),
        );
        
        //====================================================================//
        // Render Forced 
        $Crawler    =   $this->assertRouteWorks("splash_widgets_render_widget",  $Params);
        //====================================================================//
        // Verify No Enevloppe
        $Xpath  =   '//*[@data-id="' . $Type . '"]';
        $this->assertEquals(0 , $Crawler->filterXpath($Xpath)->count() );
        
    } 
    
    /**
     * @abstract    Check Widget is Rendered Even if Wrong Service
     */    
    public function assertViewFailed($Parameters)
    {
        $Xpath  =   '//*[@data-id="' . $Parameters["Type"] . '"]';
        
        //====================================================================//
        // Render Forced 
        $Crawler    =   $this->assertRouteWorks("splash_widgets_test_view_forced",  $Parameters);
        //====================================================================//
        // Verify Right Widget is Here
        $this->assertEquals(1 , $Crawler->filterXpath($Xpath)->count() );
        //====================================================================//
        // Verify Error Alert is Here
        $this->assertEquals(1 , $Crawler->filterXpath('//*[@class="alert alert-danger no-margin fade in"]')->count() );
        //====================================================================//
        // Render Forced 
        $Crawler    =   $this->assertRouteWorks("splash_widgets_test_view_delayed", $Parameters);
        //====================================================================//
        // Verify Right Widget is Here
        $this->assertEquals(1 , $Crawler->filterXpath($Xpath)->count() );
        //====================================================================//
        // Verify Error Alert is Here
        $this->assertEquals(1 , $Crawler->filterXpath('//*[@class="alert alert-danger no-margin fade in"]')->count() );
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
