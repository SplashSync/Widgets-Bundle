<?php

namespace Splash\Widgets\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

use Splash\Widgets\Services\FactoryService;
use Splash\Widgets\Services\ManagerService;

use Splash\Widgets\Tests\Services\SamplesFactoryService as SamplesFactory;

use Splash\Widgets\Tests\Blocks\Test;
use Splash\Widgets\Models\Blocks\BaseBlock;

use Splash\Widgets\Models\WidgetBase as Widget;

class A002WidgetFactoryServiceTest extends KernelTestCase
{
    /**
     * @var FactoryService
     */
    private $Factory;    
    
    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        self::bootKernel();
    }        

    /**
     * @abstract    Check Factory Class
     */    
    public function testFactoryClass()
    {
        //====================================================================//
        // Link to Widget Factory Service
        $this->Factory = static::$kernel->getContainer()->get('Splash.Widgets.Factory');
        //====================================================================//
        // Check Class
        $this->assertInstanceOf(FactoryService::class, $this->Factory);
    }      

    
    /**
     * @abstract    Check Factory Functions
     */    
    public function testFactoryCreateProcess()
    {
        //====================================================================//
        // Link to Widget Factory Service
        $this->Factory = static::$kernel->getContainer()->get('Splash.Widgets.Factory');
        
        //====================================================================//
        // Create a New Widget
        $this->Factory->Create();
        
        //====================================================================//
        // Check Widget
        $this->assertInstanceOf(Widget::class,          $this->Factory->getWidget());            
        $this->assertEquals(Null,                       $this->Factory->getWidget()->getType());            

        //====================================================================//
        // Setup Widget Options
        $this->Factory
                ->setService(SamplesFactory::SERVICE)
                ->setType(Test::TYPE)
                ->setTitle(Test::TITLE)
                ->setIcon(Test::ICON)
                ->setName(Test::TITLE)
                ->setDescription(Test::DESCRIPTION)
                ->setOrigin(SamplesFactory::ORIGIN)
            ;  
        
        //====================================================================//
        // Check Widget
        $this->assertInstanceOf(Widget::class,          $this->Factory->getWidget());            
        $this->assertEquals(Test::TYPE,                 $this->Factory->getWidget()->getType());            
        $this->assertEquals(SamplesFactory::SERVICE,    $this->Factory->getWidget()->getService());            
        $this->assertEquals(Test::TITLE,                $this->Factory->getWidget()->getTitle());            
        $this->assertEquals(Test::ICON,                 $this->Factory->getWidget()->getIcon());            
        $this->assertEquals(Test::TITLE,                $this->Factory->getWidget()->getName());            
        $this->assertEquals(Test::DESCRIPTION,          $this->Factory->getWidget()->getDescription());            
        
    }      
    
    /**
     * @abstract    Check Factory Functions
     */    
    public function testFactoryWidgetOptions()
    {
        //====================================================================//
        // Link to Widget Factory Service
        $this->Factory = static::$kernel->getContainer()->get('Splash.Widgets.Factory');
        
        //====================================================================//
        // Create a New Widget
        $this->Factory->Create();
        
        //====================================================================//
        // Check Widget
        $this->assertInstanceOf(Widget::class,              $this->Factory->getWidget());            
        $this->assertEquals(Widget::getDefaultOptions(),    $this->Factory->getWidget()->getOptions());            

        //====================================================================//
        // Setup Empty Options
        $this->Factory->setOptions([]);
        
        //====================================================================//
        // Check Widget
        $this->assertInstanceOf(Widget::class,              $this->Factory->getWidget());            
        $this->assertEquals(Widget::getDefaultOptions(),    $this->Factory->getWidget()->getOptions());            

        //====================================================================//
        // Setup Wrong Options
        $this->Factory->setOptions(["ThisOptionDoNotExists" => "This Key is Uselless"]);
        
        //====================================================================//
        // Check Widget
        $this->assertInstanceOf(Widget::class,              $this->Factory->getWidget());            
        $this->assertEquals(Widget::getDefaultOptions(),    $this->Factory->getWidget()->getOptions());            
        
        //====================================================================//
        // Setup All Options
        $this->Factory
                ->setWidth("col-test-12")
                ->setHeader(False)
                ->setFooter(False)
                ->mergeOptions(array(
                    "Color"         =>  "#666666",
                    "DatePreset"    =>  "D",
                    "UseCache"      =>  False,
                    "CacheLifeTime" =>  3,
                    "Editable"      =>  True,
                    "EditMode"      =>  True,
                ))
            ;
        
        //====================================================================//
        // Check Widget
        $this->assertInstanceOf(Widget::class,              $this->Factory->getWidget());            
        $this->assertEquals(array(
            'Width'         =>  "col-test-12",
            'Color'         =>  "#666666",
            'Header'        =>  False,
            'Footer'        =>  False,
            'DatePreset'    =>  "D",
            'UseCache'      =>  False,
            'CacheLifeTime' =>  3,
            'Editable'      =>  True,
            'EditMode'      =>  True,
        ),    $this->Factory->getWidget()->getOptions());            
        
        
    }    
    
    
    /**
     * @abstract    Check Factory Functions
     * @dataProvider BlocksNamesProvider  
     */    
    public function testFactoryWidgetBlocks($Name)
    {
        //====================================================================//
        // Link to Widget Factory Service
        $this->Factory = static::$kernel->getContainer()->get('Splash.Widgets.Factory');
        
        //====================================================================//
        // Create a New Widget
        $this->Factory->Create();
        
        //====================================================================//
        // Add Block
        $Block  =   $this->Factory->addBlock($Name);
        
        //====================================================================//
        // Build Block Type ClassName
        $BlockClassName  =    '\Splash\Widgets\Models\Blocks\\' . $Name;
        
        //====================================================================//
        // Check Block
        $this->assertInstanceOf(BaseBlock::class,           $Block);  
        $this->assertInstanceOf($BlockClassName,            $Block);            
        
        //====================================================================//
        // Check Widget
        $this->assertNotEmpty($this->Factory->getWidget()->getBlocks());            
        $this->assertEquals(1,                              $this->Factory->getWidget()->getBlocks()->count());            
        $this->assertInstanceOf($BlockClassName,            $this->Factory->getWidget()->getBlocks()->first());           
 
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
