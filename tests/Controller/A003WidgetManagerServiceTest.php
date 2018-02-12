<?php

namespace Splash\Widgets\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

use Splash\Widgets\Services\FactoryService;
use Splash\Widgets\Services\ManagerService;
use Splash\Widgets\Tests\Services\SamplesFactoryService as SamplesFactory;

use Splash\Widgets\Tests\Blocks\Test;
use Splash\Widgets\Models\Blocks\BaseBlock;

use Splash\Widgets\Models\WidgetBase        as Widget;
use Splash\Widgets\Entity\WidgetCache;

class A003WidgetManagerServiceTest extends KernelTestCase
{
    /**
     * @var ManagerService
     */
    private $Manager;
    
    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        self::bootKernel();
    }        

    /**
     * @abstract    Check Manager Class
     */    
    public function testManagerClass()
    {
        //====================================================================//
        // Link to Widget Manager Service
        $this->Manager = static::$kernel->getContainer()->get('Splash.Widgets.Manager');
        //====================================================================//
        // Check Class
        $this->assertInstanceOf(ManagerService::class, $this->Manager);
    }      

    
    /**
     * @abstract    Check Manager Listing Functions
     */    
    public function testManagerListing()
    {
        //====================================================================//
        // Link to Widget Manager Service
        $this->Manager = static::$kernel->getContainer()->get('Splash.Widgets.Manager');

        //====================================================================//
        // Get Wrong List
        $WrongList  =   $this->Manager->getList(ManagerService::TEST_WIDGETS . ".wrong");

        //====================================================================//
        // Check List
        $this->assertInternalType("array",                      $WrongList);            
        $this->assertEquals(0,                                  count($WrongList));            
        
        //====================================================================//
        // Get Test List
        $TestList   =   $this->Manager->getList(ManagerService::TEST_WIDGETS);

        //====================================================================//
        // Check List
        $this->assertInternalType("array",                      $TestList);            
        $this->assertEquals(1,                                  count($TestList));            
        
        //====================================================================//
        // Get Demo List
        $DemoList   =   $this->Manager->getList(ManagerService::DEMO_WIDGETS);

        //====================================================================//
        // Check List
        $this->assertInternalType("array",                      $DemoList);            
        $this->assertGreaterThan(1,                             count($DemoList));            
        
    }      
    
    /**
     * @abstract    Check Manager Connect Functions
     */    
    public function testManagerConnect()
    {
        //====================================================================//
        // Link to Widget Manager Service
        $this->Manager = static::$kernel->getContainer()->get('Splash.Widgets.Manager');

        //====================================================================//
        // Get Test Factory Service
        $this->assertTrue(
            $this->Manager->Connect(SamplesFactory::SERVICE)
                );

        //====================================================================//
        // Get Existant Factory Service
        $this->assertFalse(
            $this->Manager->Connect(SamplesFactory::SERVICE . "Unknown")
                );

        //====================================================================//
        // Get Existant Factory Service
        $this->expectException(\Exception::class);
        $this->Manager->Connect("router");

    }      
    
    /**
     * @abstract    Check Manager Reading Widgets Contents
     */    
    public function testManagerGetTestWidget()
    {
        //====================================================================//
        // Link to Widget Manager Service
        $this->Manager = static::$kernel->getContainer()->get('Splash.Widgets.Manager');

        //====================================================================//
        // Get Test Widget
        $TestWidget =   $this->Manager->getWidget(SamplesFactory::SERVICE, "Test");
        
        //====================================================================//
        // Check Widget
        $this->assertInstanceOf(Widget::class,          $TestWidget);            
        $this->assertEquals(Test::TYPE,                 $TestWidget->getType());     
        $this->assertGreaterThan(0,                     $TestWidget->getBlocks()->count());     
        
    }

    /**
     * @abstract    Check Manager Widgets Cache Management
     */    
    public function testManagerCache()
    {
        //====================================================================//
        // Link to Widget Manager Service
        $this->Manager = static::$kernel->getContainer()->get('Splash.Widgets.Manager');
        //====================================================================//
        // Link to Entity Manager Service
        $this->_en = static::$kernel->getContainer()->get('doctrine')->getManager();

        //====================================================================//
        // Get Test Widget
        $TestWidget =   $this->Manager->getWidget(SamplesFactory::SERVICE, "Test");
        
        //====================================================================//
        // Setup Two Widgets in Cache
        $TestWidget->setParameters([ ]);
        $this->Manager->setCacheContents($TestWidget, SamplesFactory::SERVICE );
        $TestWidget->setParameters([ Test::TYPE => SamplesFactory::SERVICE]);
        $this->Manager->setCacheContents($TestWidget, SamplesFactory::SERVICE );
        
        //====================================================================//
        // Load Both Widgets From Cache
        $Cache1 =   $this->Manager->getCache($TestWidget->getService(), $TestWidget->getType(), $TestWidget->getOptions(), [] );
        $Cache2 =   $this->Manager->getCache($TestWidget->getService(), $TestWidget->getType(), $TestWidget->getOptions(), [ Test::TYPE => SamplesFactory::SERVICE] );


        //====================================================================//
        // Check Widgets Cache
        $this->assertInstanceOf(WidgetCache::class,         $Cache1);            
        $this->assertInstanceOf(WidgetCache::class,         $Cache2);            
        $this->assertNotEquals($Cache1->getId(),            $Cache2->getId());            
        
        $this->assertEquals($TestWidget->getService(),      $Cache1->getService());            
        $this->assertEquals($TestWidget->getType(),         $Cache1->getType());            
        $this->assertEquals($TestWidget->getOptions(),      $Cache1->getOptions());            
        $this->assertEquals(SamplesFactory::SERVICE,        $Cache1->getContents());            
        
        $this->assertEquals($TestWidget->getService(),      $Cache2->getService());            
        $this->assertEquals($TestWidget->getType(),         $Cache2->getType());            
        $this->assertEquals($TestWidget->getOptions(),      $Cache2->getOptions());            
        $this->assertEquals(SamplesFactory::SERVICE,        $Cache2->getContents());            
        
        //====================================================================//
        // Set Cache1 as Expired
        $Cache1->setExpireAt(new \DateTime("-10 minute"));
        $this->_en->flush();
        
        //====================================================================//
        // ReLoad Both Widgets From Cache
        $this->assertNull(
                $this->Manager->getCache($TestWidget->getService(), $TestWidget->getType(), $TestWidget->getOptions(), [] )
                );
        $this->assertInstanceOf(
                WidgetCache::class,
                $this->Manager->getCache($TestWidget->getService(), $TestWidget->getType(), $TestWidget->getOptions(), [ Test::TYPE => SamplesFactory::SERVICE] )
                );

        //====================================================================//
        // Clean Expired Widgets From Cache
        $this->Manager->cleanCache();
        
        //====================================================================//
        // ReLoad Both Widgets From Cache
        $this->assertNull(
                $this->_en->getRepository("SplashWidgetsBundle:WidgetCache")->find($Cache1->getId())
                );
        $this->assertInstanceOf(
                WidgetCache::class,
                $this->_en->getRepository("SplashWidgetsBundle:WidgetCache")->find($Cache2->getId())
                );
        
    }
        
    public function BlocksNamesProvider()
    {
        $List   =   array();
        foreach ( ManagerService::AVAILABLE_BLOCKS as $BlockName ) {
            $List[]     =   array($BlockName);
        }
        return $List;        
    }     

}
