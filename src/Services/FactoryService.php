<?php

namespace Splash\Widgets\Services;

use Splash\Widgets\Entity\Widget;
use Splash\Widgets\Models\Blocks\BaseBlock;

/*
 * Widget Factory Service
 */
class FactoryService
{
        
    /*
     *  Widget Class 
     * 
     *  @var Widget
     */
    protected $widget;

    /*
     *  Widget Block 
     * 
     *  @var Widget
     */
    protected $block;
    
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
    public function __construct() { 
        
        $this->widget = new Widget();
        
        return True;
    }    

//====================================================================//
// *******************************************************************//
//  WIDGET PARSING FUNCTIONS
// *******************************************************************//
//====================================================================//

    /**
     * Create New Widget
     *
     * @param array     $Options
     * @param array     $Contents
     *
     * @return Widgets
     */
    public function Create($Options = Null, $Contents = Null )
    {
        //====================================================================//
        // Create an Empty Widget
        $this->widget = new Widget();
        //====================================================================//
        // Setup Widget Options
        if (!is_null($Options)) {
            $this->widget->setOptions($Options);
        }
        //====================================================================//
        // Setup Widget Contents      
        if (!is_null($Contents)) {
            $this->widget->setContents($Contents);
            $this->addBlocks($Contents["blocks"]);
        }
        return $this;
    }
    
    //====================================================================//
    // *******************************************************************//
    //  Widget Getter & Setter Functions
    // *******************************************************************//
    //====================================================================//
    
    function __call($name, $arguments) {
        if (method_exists($this->widget, $name)) {
            switch (count($arguments)) {
                case 0:
                    $this->widget->$name();
                    break;
                case 1:
                    $this->widget->$name($arguments[0]);
                    break;
                case 2:
                    $this->widget->$name($arguments[0], $arguments[1]);
                    break;
            }
        }
        return $this;         
    }
    
    /**
     * Get Current Created Widget
     *
     * @return Widget
     */
    public function getWidget()
    {
        //==============================================================================
        //  If no Id Defined, Generate Unique Widget Id 
//        if ( empty( $this->widget->getId() ) ) {
//            $this->widget->setIdentifier( md5( json_encode($this->widget) ) );
//        } 
        //==============================================================================
        //  Return Current Widget Data 
        return $this->widget;
    }
    
    
//====================================================================//
// *******************************************************************//
//  BLOCKS PARSING FUNCTIONS
// *******************************************************************//
//====================================================================//

    /**
     * add Multiple Blocks to a Widget
     *
     * @param array     $Blocks
     *
     * @return $this
     */
    public function addBlocks($Blocks)
    {
        //==============================================================================
        //  Safety Check
        if ( !is_array($Blocks) && !is_a($Blocks, "ArrayObject") ){
            return $this;
        }   
            
        foreach ($Blocks as $Block) {
            
            //====================================================================//
            // Check Block Contents are Valid
            if ( !isset($Block["type"]) || !isset($Block["data"]) ){
               continue;
            }               
            if ( empty($Block["type"]) || empty($Block["data"]) ){
               continue;
            }   
            if ( !isset($Block["options"]) || empty($Block["options"]) ){
               $Block["options"] = Null;
            } 
            //====================================================================//
            // Add Block To Widget
            $this->addBlock($Block["type"], $Block["options"], $Block["data"]);
        }        

        return $this;
    }
    
    /**
     * add New Block to Widget
     *
     * @param string    $Type
     * @param array     $Options
     * @param array     $Contents
     * 
     *
     * @return BaseBlock
     */
    public function addBlock($Type, $Options = Null, $Contents = Null )
    {
        //====================================================================//
        // Build Block Type ClassName
        $BlockClassName  =    '\Splash\Widgets\Models\Blocks\\' . $Type;
        //====================================================================//
        // Check if Requested Block Type Exists
        if ( class_exists($BlockClassName) ) {
            $this->block          =    new $BlockClassName();
        } else {
            $this->block          =    new Block();
        }
        //====================================================================//
        // Setup Parent Pointer
        $this->block->parent     =   $this;
        //====================================================================//
        // Setup Widget Options
        if (!is_null($Options)) {
            $this->block->setOptions($Options);
        }        
        //====================================================================//
        // Setup Widget Contents
        if (!is_null($Contents)) {
            $this->block->setContents($Contents);
        }              
        //====================================================================//
        // Add Block to Widget
        $this->widget->addBlock($this->block);
        return $this->block;
    }

//====================================================================//
// *******************************************************************//
//  PREDEFINED BLOCKS GENERATION
// *******************************************************************//
//====================================================================//

    public function buildErrorWidget($Service,$WidgetId,$Error)
    {
        $this
                
            //==============================================================================
            // Create Widget 
            ->Create($WidgetId)
                ->setTitle("Error")
                ->setIcon("fa fa-exclamation-triangle text-danger")
                ->setService($Service)
                ->setType($WidgetId)
            ->end()
                
            //==============================================================================
            // Create Notifications Block 
            ->addBlock("NotificationsBlock")
                ->setError($Error)
            ->end()

        ;
        
        return $this->getWidget();
    }
    
    
}