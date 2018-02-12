<?php

namespace Splash\Widgets\Services\Demo;

use Symfony\Component\Form\FormBuilderInterface;

use Splash\Widgets\Entity\Widget;

use Symfony\Component\EventDispatcher\GenericEvent;

use Splash\Widgets\Services\FactoryService;

use Splash\Widgets\Models\Interfaces\WidgetProviderInterface;

use Splash\Widgets\Models\Blocks\SparkBarChartBlock;

/*
 * Demo Widgets Factory Service
 */
class SamplesFactoryService implements WidgetProviderInterface
{
    const PREFIX    =   "Splash\Widgets\Models\Demo\Blocks\\";
    const SERVICE   =   "Splash.Widgets.Demo.Factory";

    const ORIGIN    =   "<i class='fa fa-flask text-success' aria-hidden='true'>&nbsp;</i>Demo Factory";
    
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
        
        $Event["Text"]                  =   $this->buildWidgetDefinition("Text")->getWidget();
        $Event["Table"]                 =   $this->buildWidgetDefinition("Table")->getWidget();
        $Event["Notifications"]         =   $this->buildWidgetDefinition("Notifications")->getWidget();
        $Event["SparkInfos"]            =   $this->buildWidgetDefinition("SparkInfos")->getWidget();
        $Event["SparkBar"]              =   $this->buildWidgetDefinition("SparkBar")->getWidget();
        $Event["SparkLine"]             =   $this->buildWidgetDefinition("SparkLine")->getWidget();
        $Event["Dates"]                 =   $this->buildWidgetDefinition("Dates")->getWidget();
        $Event["MorrisLine"]            =   $this->buildWidgetDefinition("MorrisLine")->getWidget();
        $Event["MorrisDonut"]           =   $this->buildWidgetDefinition("MorrisDonut")->getWidget();
        $Event["MorrisArea"]            =   $this->buildWidgetDefinition("MorrisArea")->getWidget();
        $Event["MorrisBar"]             =   $this->buildWidgetDefinition("MorrisBar")->getWidget();
        
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
                    ->setTitle($BlockClass::TITLE)
                    ->setIcon($BlockClass::ICON)
                    ->setName($BlockClass::TITLE)
                    ->setDescription($BlockClass::DESCRIPTION)
                    ->setOrigin(self::ORIGIN)
                    ->setOptions($this->getWidgetOptions($Type))
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
    public function getWidget(string $Type, $Parameters = Null)
    {
        //====================================================================//
        // If Widget Exists               
        $BlockClass = self::PREFIX . $Type;
        if (class_exists($BlockClass)) {
            
            $this->buildWidgetDefinition( $Type );
            
            $BlockClass::build($this->Factory,(is_null($Parameters) ? array() : $Parameters));
            
            return $this->Factory->getWidget();
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
        $BlockClass = self::PREFIX . $Type;

        if (class_exists($BlockClass) && method_exists($BlockClass, "getOptions")) {
            return $BlockClass::getOptions();
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
        $BlockClass = self::PREFIX . $Type;
        
        if (class_exists($BlockClass)) {
            $BlockClass::populateWidgetForm($builder);
        }
        
        if ( $Type == "SparkBarChartWidget" ) {
            SparkBarChartBlock::addHeightFormRow($builder);
            SparkBarChartBlock::addBarWidthFormRow($builder);
            SparkBarChartBlock::addBarColorFormRow($builder);
        } 
        
        return;
    }

}