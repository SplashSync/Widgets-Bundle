<?php

namespace Splash\Widgets\Services;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use Nodes\CoreBundle\Entity\Node;    
use OpenObject\CoreBundle\Document\OpenObjectFieldCore  as Field;

use Splash\Widgets\Entity\Widget;
use Splash\Widgets\Models\WidgetBlock    as Block;

use Symfony\Component\EventDispatcher\GenericEvent;

use Splash\Widgets\Services\FactoryService;

use Splash\Widgets\Models\Interfaces\WidgetProviderInterface;

use ArrayObject;

/*
 * Demo Widgets Factory Service
 */
class SamplesFactoryService implements WidgetProviderInterface
{
    const SERVICE    =   "Splash.Widgets.Samples";
    
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
        
        $Event["TextWidget"]        =   $this->buildWidgetDefinition("TextWidget", "Sample Text Widget");
        $Event["TableWidget"]       =   $this->buildWidgetDefinition("TableWidget", "Sample Table Widget");
        $Event["NotificationWidget"]=   $this->buildWidgetDefinition("NotificationWidget", "Sample Notification Widget");
        $Event["SparkInfoWidget"]   =   $this->buildWidgetDefinition("SparkInfoWidget", "Sample Notification Widget");
        
        return True;
    }  
    
    /**
     * @abstract    Widgets Listing
     */    
    public function buildWidgetDefinition( $Type , $Name, $Desc = Null, $Options = array()) { 
        
        $this->Factory
                ->Create($Type)
                ->setService(self::SERVICE)
                ->setType($Type)
                ->setName($Name)
                ->setDescription(($Desc ? $Desc : $Name))
            ;    

        return $this->Factory->getWidget();
    } 
    
    /**
     *      @abstract   Read Widget Contents
     * 
     *      @param      string  $WidgetId         Widgets Type Identifier 
     * 
     *      @return     Widget 
     */    
    public function getWidget($WidgetId)
    {
        //====================================================================//
        // If Widget Exists               
        if ( method_exists($this, $WidgetId) ) {
            return $this->$WidgetId();
        } 
        
        return Null;
    }      
    
    /**
     * @abstract   Return Widget Options Array 
     * 
     * @param      string   $WidgetId           Widgets Type Identifier 
     * 
     * @return     array
     */    
    public function getWidgetOptions($WidgetId) : array
    {
        return Widget::OPTIONS;
    }

    /**
     * @abstract   Return Widget Parameters Array 
     * 
     * @param      string   $WidgetId           Widgets Type Identifier 
     * 
     * @return     array
     */    
    public function getWidgetParameters($WidgetId) : array
    {
        return array();
    }
        
    
    /**
     * @abstract   Return Widget Parameters Array 
     * 
     * @param      string   $WidgetId           Widgets Type Identifier 
     * @param      array    $Parameters         Updated Parameters 
     * 
     * @return     array
     */    
    public function setWidgetParameters($WidgetId, $Parameters) : bool 
    {
        return True;
    }
    
    
    /**
     * @abstract   Return Widget Parameters Fields Array 
     * 
     * @param      string   $WidgetId           Widgets Type Identifier 
     * 
     * @return     array
     */    
    public function getWidgetParametersFields($WidgetId) : array
    {
        return array();
    }
    
    
//====================================================================//
// *******************************************************************//
//  DEMO WIDGETS FACTORY
// *******************************************************************//
//====================================================================//
    
    
    public function TextWidget()
    {
        //==============================================================================
        // Create Widget Options 
        $WidgetOptions = array(
            "Footer"          =>  True,
        );

        $this->Factory
            //==============================================================================
            // Create Widget 
            ->Create("Text-Wid",$WidgetOptions)
                ->setTitle('Simple Text Widget')
                ->setSubTitle('Simple SubTitle Text!!')
                ->setIcon('fa fa-binoculars')
                ->setWidth("sm")
                ->setOrigin("Sample Widgets Factory")
            ->end()
            //==============================================================================
            // Create Text Block 
            ->addBlock("TextBlock", array( "AllowHtml" => True ) )
                ->setText("This is demo Simple Text Block. You can use it to render <b>HTML Contents</b>.")
            ->end();
                
         return $this->Factory->getWidget();
    }    
    
    public function TableWidget()
    {
        $this->Factory
                
            //==============================================================================
            // Create Widget 
            ->Create("Notify-Wid")
                ->setTitle('My First Table Widget')
                ->setWidth("sm")
            ->end()
                
            //==============================================================================
            // Create Table Block 
            ->addBlock("TableBlock" , array( "AllowHtml" => True ))
                ->addRow(["One", "Two", "Treeee!"])
                ->addRow(["One", "<b>Two</b>", "Treeee!"])
                ->addRow(["One", "Two", "Treeee!"])
                ->addRow(["One", "Two", "Treeee!"])
            ->end()
                
            //==============================================================================
            // Create Text Block 
            ->addBlock("TextBlock")
                ->setText("This is a demo of Widget Notification Block. This may be used on all widgets!")
            ->end();
        
        return $this->Factory->getWidget();
    }
    
    public function NotificationWidget()
    {
        $this->Factory
                
            //==============================================================================
            // Create Widget 
            ->Create("Notify-Wid")
                ->setTitle('Notification Widget')
                ->setWidth("sm")
            ->end()
                
            //==============================================================================
            // Create Notifications Block 
            ->addBlock("NotificationsBlock")
                ->setError("My Widget Error")
                ->setWarning("My Widget Warning")
                ->setInfo("My Widget Info")
                ->setSuccess("My Widget Success")
            ->end()
                
            //==============================================================================
            // Create Text Block 
            ->addBlock("TextBlock")
                ->setText("This is a demo of Widget Notification Block. This may be used on all widgets!")
            ->end();
        
        return $this->Factory->getWidget();
    }
    
    
    public function SparkInfoWidget()
    {
        //==============================================================================
        // Create Widget Options 
        $WidgetOptions = array(
            "Header"          =>  False,
            "Footer"          =>  False,
        );        
        
        //==============================================================================
        // Create Block Options 
        $BlockOptions = array(
//            "Width"          =>  "",
        );  
        
        $this->Factory
                
            //==============================================================================
            // Create Widget 
            ->Create("Notify-Wid", $WidgetOptions)
                ->setTitle('Spark Infos Widget')
                ->setWidth("xl")
            ->end()
                
            //==============================================================================
            // Create SparkInfo Block 
            ->addBlock("SparkInfoBlock",$BlockOptions)
                ->setTitle("Glyph Icon")
                ->setGlyphIcon("asterisk")
                ->setValue("100%")
                ->setChart(array(1300, 1877, 2500, 400,240,220,310,220,300))
                ->setSeparator(True)
            ->end()
                
            //==============================================================================
            // Create SparkInfo Block 
            ->addBlock("SparkInfoBlock",$BlockOptions)
                ->setTitle("Glyph Icon")
                ->setGlyphIcon("asterisk")
                ->setValue("100%")
                ->setChart(array(1300, 1877, 2500, 400,240,220,310,220,300))
                ->setSeparator(True)
            ->end()
                
            //==============================================================================
            // Create SparkInfo Block 
            ->addBlock("SparkInfoBlock",$BlockOptions)
                ->setTitle("Glyph Icon")
                ->setGlyphIcon("asterisk")
                ->setValue("100%")
                ->setChart(array(1300, 1877, 2500, 400,240,220,310,220,300))
                ->setSeparator(True)
            ->end()
                
            //==============================================================================
            // Create SparkInfo Block 
            ->addBlock("SparkInfoBlock",$BlockOptions)
                ->setTitle("Glyph Icon")
                ->setGlyphIcon("asterisk")
                ->setValue("100%")
                ->setChart(array(1300, 1877, 2500, 400,240,220,310,220,300))
                ->setSeparator(True)
            ->end()
                
                
        ;
        
        return $this->Factory->getWidget();
    }
    
    public function CompositeWidget()
    {
        
        $this->Factory
                
            //==============================================================================
            // Create Widget 
            ->Create("Composite-Wid")
                ->setTitle('Composite Data Widget')
                ->setWidth("xl")
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