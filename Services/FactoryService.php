<?php

namespace Splash\Widgets\Services;

use Splash\Widgets\Entity\Widget;

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
        // Setup Widget Identifier
        if (!empty($Identifier)) {
            $this->widget->setIdentifier($Identifier);
        }
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
     * @return Block
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
     * @return Block
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
//  WIDGET READING
// *******************************************************************//
//====================================================================//

    /**
     * Use Widget Service to Load Widget Contents
     *
     * @param ArrayObject      $Widget
     *
     * @return array
     */
    public function loadWidget($Widget)
    {
        //==============================================================================
        // Verify Item Service is Available 
        if ( !$this->t->hasService($Widget->Service)) {
            return Null;
        }
        //==============================================================================
        // Read Widget Contents 
        $WidgetContents =   $this->t
                ->getService($Widget->Service)
                ->getWidget($Widget->getIdentifier(), Null , $Widget->Parameters);
           
        if ( isset($Widget->Identifier) && !empty($Widget->Identifier) ) {
            $Id =   $Widget->Identifier;
        } else {
            $Id =   $Widget->Type;
        }
        //==============================================================================
        //Create Widget Model Entity 
        return $this->Create(
                $Id, 
                $Widget->Options, 
                $WidgetContents);
    }    
//
////====================================================================//
//// *******************************************************************//
////  WIDGET RENDERING OPTIONS
//// *******************************************************************//
////====================================================================//    
//    
//    /**
//     * Populate Widget Options Form
//     *
//     * @param FormBuilder       $FormBuilder
//     * @param ArrayObject       $Widget
//     *
//     * @return none
//     */
//    public function populateWidgetForm(FormBuilder $FormBuilder, $Fields, $SelectDates = False)
//    {
////        //==============================================================================
////        // Verify Item Service is Available 
////        if ( $SelectDates ) {
////            //====================================================================//
////            // Widget Option - Select Dates 
////            //====================================================================//
////
////            $FormBuilder->add("Dates", ChoiceType::class, array(
////                'required'                  => True,
////                'property_path'             => 'options[DatePreset]',
////                'label'                     => "options.dates.label",
//////                'help_block'                => "options.dates.tooltip",
////                'choices'                   => array(
////                    "D"         =>      "options.dates.day", 
////                    "W"         =>      "options.dates.week", 
////                    "M"         =>      "options.dates.month", 
////                    "Y"         =>      "options.dates.year", 
////                    "PD"        =>      "options.dates.prev_day", 
////                    "PW"        =>      "options.dates.prev_week", 
////                    "PM"        =>      "options.dates.prev_month", 
////                    "PY"        =>      "options.dates.prev_year", 
////                    ),
////                'empty_data'                => "options.dates.M",
////                'translation_domain'        => "SplashWidgetsBundle",
////                'choice_translation_domain' => True,            
//////                'choices_as_values'         => False,            
////                'placeholder'               => False,
//////                'widget_type'               => 'inline',
////                'expanded'                  => false,
////                ));
////        }
//        
//        
//        //====================================================================//
//        // Widget Option - Box Bootstrap Width  
//        //====================================================================//
//
//        $FormBuilder->add("Width", ChoiceType::class, array(
//            'required'                  => True,
//            'property_path'             => 'options[Width]',
//            'label'                     => "options.width.label",
//            'help_block'                => "options.width.tooltip",
//            'choices'                   => array(
//                "options.width.xs"          => Widget::$WIDTH_XS, 
//                "options.width.sm"          => Widget::$WIDTH_SM, 
//                "options.width.m"           => Widget::$WIDTH_M, 
//                "options.width.l"           => Widget::$WIDTH_L, 
//                "options.width.xl"          => Widget::$WIDTH_XL, 
//                ),
//            'empty_data'                => "options.width.xl",
//            'translation_domain'        => "SplashWidgetsBundle",
//            'choice_translation_domain' => True,            
//            'choices_as_values'         => True,            
//            'placeholder'               => False,
//            'widget_type'               => 'inline',
//            'expanded'                  => false,
//            ));
//        
//        //====================================================================//
//        // Widget Option - Diasble Header Display 
//        //====================================================================//
//
////        //==============================================================================
////        // Simple CheckBox 
////        $FormBuilder->add("Header", ChoiceType::class, array(
////                'property_path'             => 'options[Header]',            
////                'label'                     => "options.header.label",
//////                'help_block'                => "options.header.tooltip",
////                'translation_domain'        => "SplashWidgetsBundle",
////                'choices'               => array(
////                    '0' => "actions.disable", 
////                    '1' => "actions.enable", 
////                    ),
////                'empty_data'                => "1",
////                'choice_translation_domain' => "ThemeBundle",            
////                'expanded'              => true,
////                'attr'            => array (
////                    'class'             => 'col-sm-4',
////                    'widget_class'      => 'radiobox style-3',
////                ),
//////                'horizontal_input_wrapper_class'    => "col-sm-9",   
////            
////            ));          
////            
////        //====================================================================//
////        // Widget Option - Diasble Footer Display 
////        //====================================================================//
////
////        $FormBuilder->add("Footer", ChoiceType::class, array(
////                'property_path'             => 'options[Footer]',            
////                'label'                     => "options.footer.label",
//////                'help_block'                => "options.footer.tooltip",
////                'translation_domain'        => "SplashWidgetsBundle",
////                'choices'               => array(
////                    '0' => "actions.disable", 
////                    '1' => "actions.enable", 
////                    ),
////                'empty_data'                => "1",
////                'choice_translation_domain' => "ThemeBundle",            
////                'expanded'              => true,
////                'attr'            => array (
////                    'class'             => 'col-sm-4',
////                    'widget_class'      => ' radiobox style-3',
////                ),
//////                'horizontal_input_wrapper_class'    => "col-sm-9",   
////                ));         
//        
//        
////        //==============================================================================
////        // Verify Parameter Fields are Available 
////        if ( empty($Fields) ) {
////            return Null;
////        }
////        
////        //==============================================================================
////        // Add Parameters to Widget Form 
////        foreach ($Fields as $Field) {
////            $FieldClassName =   Field::isValidType($Field["type"]);
////            if ( $FieldClassName  ) {
////                    $FieldClassName::buildForm($FormBuilder, $Field );
////            } 
////        }
//        
////        $FormBuilder->setAttribute('tabbed', false);
////        $FormBuilder->setOption('tabbed', false);
//    }  
//    
    
////====================================================================//
//// *******************************************************************//
////  WIDGET FACTORY FUNCTIONS
//// *******************************************************************//
////====================================================================//
//
//    /**
//     * Get Membership Account Type Defnition data 
//     * 
//     * @param   string  $Type           Account Type
//     * @param   string  $Name           Data Name
//     * @param   string  $Domain         Data Domain Name
//     * 
//     */
//    public function getAccountTypeData($Type,$Name,$Domain = Null)
//    {
//        if ( $Domain ) {
//            if ( isset($this->AccountsType[$Type][$Domain][$Name]) ) {
//                return $this->AccountsType[$Type][$Domain][$Name];
//            }
//        }elseif ( isset($this->AccountsType[$Type][$Name]) ) {
//            return $this->AccountsType[$Type][$Name];
//        }
//        return "Not Found...";
//    } 

    public function buildErrorWidget($Service,$WidgetId,$Error)
    {
        $this
                
            //==============================================================================
            // Create Widget 
            ->Create($WidgetId)
                ->setTitle($Service . " => " . $WidgetId)
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