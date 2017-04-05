<?php

namespace Splash\Widgets\Services;

use Symfony\Component\Form\FormBuilderInterface;

use Splash\Widgets\Entity\Widget;
use Splash\Widgets\Models\WidgetBlock    as Block;

use Symfony\Component\EventDispatcher\GenericEvent;

use Splash\Widgets\Services\FactoryService;

use Splash\Widgets\Models\Interfaces\WidgetProviderInterface;

use Splash\Widgets\Models\Blocks\SparkBarChartBlock;

/*
 * Demo Widgets Factory Service
 */
class SamplesFactoryService implements WidgetProviderInterface
{
    const PREFIX    =   "Splash\Widgets\Services\DemoBlocks\\";
    
    const SERVICE   =   "Splash.Widgets.Samples";
    
    const DEFAULT_WIDGET    =   array(
            "Identifier"    =>  Null,
            "Node"          =>  Null,
            "Service"       =>  "OpenWidgets.Core.SamplesFactory", 
            "Type"          =>  Null,
            "Name"          =>  Null,
            "Description"   =>  Null,
            "Parameters"    =>  Null,
            "Options"        =>  Null,
        );
    
    /**
     * WidgetFactory Service
     * 
     * @var Splash\Widgets\Services\FactoryService 
     */    
    private $Factory;
    
    /*
     *  Fault String
     */
    public $fault_str;    

//====================================================================//
//  CONSTRUCTOR
//====================================================================//
    
    /**
     *      @abstract    Class Constructor
     */    
    public function __construct(FactoryService $WidgetFactory) { 
        //====================================================================//
        // Link to WidgetFactory Service
        $this->Factory = $WidgetFactory;
        return True;
    }    


    /**
     * @abstract    Widgets Listing
     */    
    public function onListingAction( GenericEvent $Event ) { 
        
        $Event["TextWidget"]            =   $this->buildWidgetDefinition("TextWidget", "Sample Text Widget")->getWidget();
        $Event["TableWidget"]           =   $this->buildWidgetDefinition("TableWidget", "Sample Table Widget")->getWidget();
        $Event["NotificationWidget"]    =   $this->buildWidgetDefinition("NotificationWidget", "Sample Notification Widget")->getWidget();
        $Event["SparkInfoWidget"]       =   $this->buildWidgetDefinition("SparkInfoWidget", "Sample Notification Widget")->getWidget();
        $Event["SparkBarChartWidget"]   =   $this->buildWidgetDefinition("SparkBarChartWidget", "Sample Bar Chart Widget")->getWidget();
        
        $Event["SparkLine"]             =   $this->buildWidgetDefinition("SparkLine")->getWidget();
        
        return True;
    }  
    
    /**
     * @abstract    Widgets Listing
     */    
    public function buildWidgetDefinition( $Type , $Name = Null, $Desc = Null, $Options = array()) { 

        $BlockClass = self::PREFIX . $Type;
        
        if (class_exists($BlockClass)) {
            $this->Factory
                    ->Create($Type)
                    ->setService(self::SERVICE)
                    ->setType($BlockClass::TYPE)
                    ->setName($BlockClass::TITLE)
                    ->setDescription($BlockClass::DESCRIPTION)
                    ->setOrigin("Demo Factory")
                ;    
            
        } else {
            $this->Factory
                    ->Create($Type)
                    ->setService(self::SERVICE)
                    ->setType($Type)
                    ->setName($Name)
                    ->setDescription(($Desc ? $Desc : $Name))
                    ->setOrigin("Demo Factory")
                ;    
        }
        

        return $this->Factory;
    } 
    
    /**
     * @abstract   Read Widget Contents
     * 
     * @param      string   $Type               Widgets Type Identifier 
     * @param      array    $Parameters         Widget Parameters
     * 
     * @return     Widget 
     */    
    public function getWidget(string $Type, array $Parameters = array())
    {
        //====================================================================//
        // If Widget Exists               
        if ( method_exists($this, $Type) ) {
            return $this->$Type($Parameters);
        } 
        
        $BlockClass = self::PREFIX . $Type;
        
        if (class_exists($BlockClass)) {
            
            $this->buildWidgetDefinition( $Type );
            
            $this->Factory
                    ->Create($Type)
                    ->setService(self::SERVICE)
                    ->setType($BlockClass::TYPE)
                    ->setName($BlockClass::TITLE)
                    ->setDescription($BlockClass::DESCRIPTION)
                    ->setOrigin("Demo Factory")
                ;    
            
        }
        
        return Null;
    }      
    
    /**
     * @abstract   Return Widget Options Array 
     * 
     * @param      string   $Type               Widgets Type Identifier 
     * 
     * @return     array
     */    
    public function getWidgetOptions(string $Type) : array
    {
        //====================================================================//
        // If Widget Exists               
        if ( method_exists($this, $Type) ) {
            $Widget = $this->$Type();
            return $Widget->getOptions();
        } 
        return Widget::getDefaultOptions();
    }

    /**
     * @abstract   Update Widget Options Array 
     * 
     * @param      string   $Type               Widgets Type Identifier 
     * @param      array    $Options            Updated Options 
     * 
     * @return     array
     */    
    public function setWidgetOptions(string $Type, array $Options) : bool
    {
        return True;
    }
    
    /**
     * @abstract   Return Widget Parameters Array 
     * 
     * @param      string   $Type               Widgets Type Identifier 
     * 
     * @return     array
     */    
    public function getWidgetParameters(string $Type) : array
    {
        return array();
    }
        
    
    /**
     * @abstract   Update Widget Parameters Array 
     * 
     * @param      string   $Type               Widgets Type Identifier 
     * @param      array    $Parameters         Updated Parameters 
     * 
     * @return     array
     */    
    public function setWidgetParameters(string $Type, array $Parameters) : bool
    {
        return True;
    }
    
    
    
    /**
     * @abstract   Return Widget Parameters Fields Array 
     * 
     * @param FormBuilderInterface  $builder
     * @param      string           $Type           Widgets Type Identifier 
     * 
     * @return     array
     */    
    public function populateWidgetForm(FormBuilderInterface $builder, string $Type)
    {
        
        if ( $Type == "SparkBarChartWidget" ) {
            SparkBarChartBlock::addHeightFormRow($builder);
            SparkBarChartBlock::addBarWidthFormRow($builder);
            SparkBarChartBlock::addBarColorFormRow($builder);
        } 
        
        return;
    }

    
//====================================================================//
// *******************************************************************//
//  DEMO WIDGETS FACTORY
// *******************************************************************//
//====================================================================//
    
    
    public function TextWidget()
    {
        $this->Factory
            //==============================================================================
            // Create Widget 
            ->Create()
                ->setService(self::SERVICE)
                ->setType(__FUNCTION__)
                ->setTitle('Simple Text Widget')
                ->setSubTitle('Simple SubTitle Text!!')
                ->setIcon('fa fa-binoculars')
                ->setWidth("sm")
                ->setOrigin("Sample Widgets Factory")
            ->end()
            //==============================================================================
            // Create Text Block 
            ->addBlock("TextBlock", array( "AllowHtml" => True ) )
                ->setText("<p>This is demo Simple Text Block. You can use it to render <b>Raw HTML Contents</b>.</p>")
            ->end();
                
         return $this->Factory->getWidget();
    }    
    
    public function TableWidget()
    {
        $this->Factory
                
            //==============================================================================
            // Create Widget 
            ->Create()
                ->setService(self::SERVICE)
                ->setType(__FUNCTION__)
                ->setTitle('My First Table Widget')
                ->setWidth(Widget::$WIDTH_SM)
                ->setOrigin("Sample Widgets Factory")
            ->end()
                
            //==============================================================================
            // Create Text Block 
            ->addBlock("TextBlock", array( "AllowHtml" => True ) )
                ->setText("<p>This is demo Table Block. You can use it to render... <b>data tables</b>.</p>")
            ->end()
        
            //==============================================================================
            // Create Table Block 
            ->addBlock("TableBlock" , array( "AllowHtml" => True ))
                ->addRow(["One", "Two", "Treeee!"])
                ->addRow(["One", "<b>Two</b>", "Treeee!"])
                ->addRow(["One", "Two", "Treeee!"])
                ->addRow(["One", "Two", "Treeee!"])
            ->end()
                
        ;
        
        return $this->Factory->getWidget();
    }
    
    public function NotificationWidget()
    {
        $this->Factory
                
            //==============================================================================
            // Create Widget 
            ->Create()
                ->setService(self::SERVICE)
                ->setType(__FUNCTION__)
                ->setTitle('Notification Widget')
                ->setWidth(Widget::$WIDTH_SM)
                ->setOrigin("Sample Widgets Factory")
            ->end()
                
            //==============================================================================
            // Create Text Block 
            ->addBlock("TextBlock", array( "AllowHtml" => True ) )
                ->setText("<p>This is demo Notification Block. You can use it to render <b>any kind of alerts</b>.</p>")
            ->end()
                        
            //==============================================================================
            // Create Notifications Block 
            ->addBlock("NotificationsBlock")
                ->setError("My Widget Error")
                ->setWarning("My Widget Warning")
                ->setInfo("My Widget Info")
                ->setSuccess("My Widget Success")
            ->end()
                
        ;       
        
        return $this->Factory->getWidget();
    }
    
    public function SparkInfoWidget()
    {
        //==============================================================================
        // Create Widget Options 
//        $WidgetOptions = array(
//            "Header"          =>  False,
//            "Footer"          =>  False,
//        );        
        
        //==============================================================================
        // Create Block Options 
        $BlockOptions = array(
            "Width"          => Widget::$WIDTH_XS,
        );  
        
        $this->Factory
                
            //==============================================================================
            // Create Widget 
            ->Create()
                ->setService(self::SERVICE)
                ->setType(__FUNCTION__)
                ->setTitle('Spark Infos Widget')
                ->setWidth(Widget::$WIDTH_DEFAULT)
                ->setOrigin("Sample Widgets Factory")
            ->end()
                
            //==============================================================================
            // Create SparkInfo Block 
            ->addBlock("SparkInfoBlock",$BlockOptions)
                ->setTitle("Fontawesome Icon")
                ->setFaIcon("magic")
                ->setValue("100%")
                ->setSeparator(True)
            ->end()
                
            //==============================================================================
            // Create SparkInfo Block 
            ->addBlock("SparkInfoBlock",$BlockOptions)
                ->setTitle("Glyph Icon")
                ->setGlyphIcon("asterisk")
                ->setValue("100%")
                ->setSeparator(True)
            ->end()
                
            //==============================================================================
            // Create SparkInfo Block 
            ->addBlock("SparkInfoBlock",$BlockOptions)
                ->setTitle("Sparkline Chart")
                ->setChart(array("0:30", "10:20", "20:20", "30:20", "-10:10", "15:25", "30:40", "80:90", 90, 100, 90, 80))
                ->setSeparator(True)
            ->end()
                
            //==============================================================================
            // Create SparkInfo Block 
            ->addBlock("SparkInfoBlock",$BlockOptions)
                ->setTitle("Sparkline Pie")
                ->setPie(array("10", "20", "30"))
                ->setSeparator(True)
            ->end()
                
//                
//            //==============================================================================
//            // Create SparkInfo Block 
//            ->addBlock("SparkInfoBlock",$BlockOptions)
//                ->setTitle("Fa Icon")
//                ->setFaIcon("magic")
////                ->setTitle("Glyph Icon")
////                ->setGlyphIcon("asterisk")
//                ->setValue("100%")
//                ->setChart(array(1300, 1877, 2500, 400,240,220,310,220,300))
//                ->setSeparator(True)
//            ->end()
                
                
        ;
        
        return $this->Factory->getWidget();
    }
   
    public function SparkBarChartWidget($Parameters)
    {
        //==============================================================================
        // Create Widget Options 
//        $WidgetOptions = array(
//            "Header"          =>  False,
//            "Footer"          =>  False,
//        );        
        
        //==============================================================================
        // Create Block Options 
        $BlockOptions = array(
            "Width"          => Widget::$WIDTH_XL,
        );  
        
        $this->Factory
                
            //==============================================================================
            // Create Widget 
            ->Create()
                ->setService(self::SERVICE)
                ->setType(__FUNCTION__)
                ->setTitle('Bar Chart Widget')
                ->setWidth(Widget::$WIDTH_DEFAULT)
                ->setOrigin("Sample Widgets Factory")
            ->end()
                
        ;
        
            //==============================================================================
            // Create SparkInfo Block 
        $BarGraph = $this->Factory->addBlock("SparkBarChartBlock",$BlockOptions);
        
        $BarGraph
                ->setTitle("Sparkline Bar Chart")
                ->setValues(array("1", "3", "5", "7", "12", "24", "32", "64", "36", "24", "-5", "30", "10", "20", "30", "10", "20", "30"));
        
        if ( isset($Parameters["sparkbar_height"]) ) {
            $BarGraph->setChartHeight($Parameters["sparkbar_height"]);
        }
        
        if ( isset($Parameters["sparkbar_barwidth"]) ) {
            $BarGraph->setBarWidth($Parameters["sparkbar_barwidth"]);
        }
        if ( isset($Parameters["sparkbar_barcolor"]) ) {
            $BarGraph->setBarColor($Parameters["sparkbar_barcolor"]);
        }
        
//            ->end()
                
//                
//            //==============================================================================
//            // Create SparkInfo Block 
//            ->addBlock("SparkInfoBlock",$BlockOptions)
//                ->setTitle("Fa Icon")
//                ->setFaIcon("magic")
////                ->setTitle("Glyph Icon")
////                ->setGlyphIcon("asterisk")
//                ->setValue("100%")
//                ->setChart(array(1300, 1877, 2500, 400,240,220,310,220,300))
//                ->setSeparator(True)
//            ->end()
                
                
        ;
        
        return $this->Factory->getWidget();
    }
    
    public function CompositeWidget()
    {
        
        $this->Factory
                
            //==============================================================================
            // Create Widget 
            ->Create()
                ->setTitle('Composite Data Widget')
                ->setWidth(Widget::$WIDTH_XL)
                ->setOrigin("Sample Widgets Factory")
            ->end()
                
            //==============================================================================
            // Create Table Block 
            ->addBlock("TableBlock" , array( "AllowHtml" => True ))
                ->addRow(["One", "Two", "Treeee!"])
                ->addRow(["One", "<b>Two</b>", "Treeee!"])
                ->addRow(["One", "Two", "Treeee!"])
                ->addRow(["One", "Two", "Treeee!"])
                ->setWidth(Block::SIZE_SM)
            ->end()
                
            //==============================================================================
            // Create Notifications Block 
            ->addBlock("NotificationsBlock")
                ->setError("My Widget Error")
                ->setWarning("My Widget Warning")
                ->setInfo("My Widget Info")
                ->setSuccess("My Widget Success")
                ->setWidth(Block::SIZE_SM)                
            ->end()
                
            //==============================================================================
            // Create SparkInfo Block 
            ->addBlock("SparkInfoBlock")
                ->setTitle("Glyph Icon")
                ->setGlyphIcon("asterisk")
                ->setValue("100%")
                ->setChart(array(1300, 1877, 2500, 400,240,220,310,220,300))
                ->setSeparator(True)
            ->end()
                
            //==============================================================================
            // Create SparkInfo Block 
            ->addBlock("SparkInfoBlock")
                ->setTitle("Glyph Icon")
                ->setGlyphIcon("asterisk")
                ->setValue("100%")
                ->setChart(array(1300, 1877, 2500, 400,240,220,310,220,300))
                ->setSeparator(True)
            ->end()
                
            //==============================================================================
            // Create SparkInfo Block 
            ->addBlock("SparkInfoBlock")
                ->setTitle("Glyph Icon")
                ->setGlyphIcon("asterisk")
                ->setValue("100%")
                ->setChart(array(1300, 1877, 2500, 400,240,220,310,220,300))
                ->setSeparator(True)
            ->end()
        ;
        
        return $this->Factory->getWidget();
    }      
    
}