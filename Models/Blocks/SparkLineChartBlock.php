<?php

/*
 * This file is part of the Splash Sync project.
 *
 * (c) Bernard Paquier <pro@bernard-paquier.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Splash\Widgets\Models\Blocks;

use Splash\Widgets\Entity\Widget;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

/**
 * Abstact Widget Model 
 * 
 * @author Bernard Paquier <pro@bernard-paquier.fr>
 */
class SparkLineChartBlock extends BaseBlock
{

    //====================================================================//
    // *******************************************************************//
    //  BLOCK GENERICS PARAMETERS
    // *******************************************************************//
    //====================================================================//

    //====================================================================//
    // Define Standard Data Fields for this Widget Block
    static $DATA          = array(
        "title"             => "Title",
        "values"            => array(),
    );

    //====================================================================//
    // Define Standard Options for this Widget Block
    // Uncomment to override défault options
    static $OPTIONS       = array(
        'Width'             => "col-sm-12 col-md-12 col-lg-12",
        "AllowHtml"         => False,    
        "Graph"             => array(
            "height"    =>  "180",
            "width"     =>  "96%",
            "color"  =>  "20"
        ),
    );

        
    /**
     * @var string
     */
    protected $type = "SparkLineChartBlock";
    
    /**
     * Set Block Contents from Array
     *
     * @param array $Contents
     *
     * @return Widget
     */
    public function setContents($Contents)
    {
        //==============================================================================
        //  Safety Check
        if ( !is_array($Contents) && !is_a($Contents, "ArrayObject") ){
            return $this;
        } 
        
        //==============================================================================
        //  Import Title
        if ( !empty($Contents["title"]) ){
            $this->setTitle($Contents["title"]);
        }         
        //==============================================================================
        //  Import Values
        if ( !empty($Contents["values"]) ){
            $this->setValues($Contents["values"]);
        }         
     
        return $this;
    }      
    
    /**
     * @abstract   Add SparkBar Height Parameter to Widget Form
     */    
    public static function addHeightFormRow(FormBuilderInterface $builder)
    {
        $builder->add('SparkLineHeight', IntegerType::class, array(
            'label'                 => "blocks.sparkbar.height.label",
            'help_block'            => "blocks.sparkbar.height.tooltip",
            'translation_domain'    => "SplashWidgetsBundle",
            'property_path'         => 'parameters[sparkbar_height]',
            'required'              => False,
        ));
    }
    
    /**
     * @abstract   Add SparkBar Bar Width Parameter to Widget Form
     */    
    public static function addBarWidthFormRow(FormBuilderInterface $builder)
    {
        $builder->add('SparkLineBarWidth', IntegerType::class, array(
            'label'                 => "blocks.sparkbar.barwidth.label",
            'help_block'            => "blocks.sparkbar.barwidth.tooltip",
            'translation_domain'    => "SplashWidgetsBundle",
            'property_path'         => 'parameters[sparkbar_barwidth]',
            'required'              => False,
        ));
    }
    
    /**
     * @abstract   Add SparkBar Bar Color Parameter to Widget Form
     */    
    public static function addBarColorFormRow(FormBuilderInterface $builder)
    {
        $builder->add('SparkLineBarColor', TextType::class, array(
            'label'                 => "blocks.sparkbar.barcolor.label",
            'help_block'            => "blocks.sparkbar.barcolor.tooltip",
            'translation_domain'    => "SplashWidgetsBundle",
            'property_path'         => 'parameters[sparkbar_barcolor]',
            'required'              => False,
        ));
    }
    
    //====================================================================//
    // *******************************************************************//
    //  Block Getter & Setter Functions
    // *******************************************************************//
    //====================================================================//
    
    /**
     * Set Title
     * 
     * @param   $text
     * 
     * @return  Widget
     */
    public function setTitle($text)
    {
        $this->data["title"]     =   $text;
        return $this;
    }
    
    /**
     * Get Title
     * 
     * @return  String
     */
    public function getTitle()
    {
        return $this->data["title"];
    }   
       
    
    /**
     * Set Values
     * 
     * @param   $text
     * 
     * @return  Widget
     */
    public function setValues($text)
    {
        $this->data["values"]     =   $text;
        return $this;
    }
    
    /**
     * Get Values
     * 
     * @return  String
     */
    public function getValues()
    {
        return $this->data["values"];
    }    

    /**
     * Set Chart Height
     */
    public function setChartHeight($value)
    {
        $this->options["Graph"]["height"]     =   $value;
        return $this;
    }
    
    /**
     * Set Bar Width
     */
    public function setChartWidth($value)
    {
        $this->options["Graph"]["width"]     =   $value;
        return $this;
    }
    
    /**
     * Set Bar Width
     */
    public function setBarWidth($value)
    {
        $this->options["Graph"]["barwidth"]     =   $value;
        return $this;
    }

    /**
     * Set Bar Width
     */
    public function setBarColor($value)
    {
        $this->options["Graph"]["bar-color"]     =   $value;
        return $this;
    }
    
}
