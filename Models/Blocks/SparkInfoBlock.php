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

use Symfony\Component\OptionsResolver\OptionsResolver;

use Splash\Widgets\Entity\Widget;

/**
 * Abstact Widget Model 
 * 
 * @author Bernard Paquier <pro@bernard-paquier.fr>
 */
class SparkInfoBlock extends BaseBlock
{

    //====================================================================//
    // *******************************************************************//
    //  BLOCK GENERICS PARAMETERS
    // *******************************************************************//
    //====================================================================//

    //====================================================================//
    // Define Standard Data Fields for this Widget Block
    static $DATA          = array(
        "color"             => "defaut",
        "title"             => "Title",
        "fa_icon"           => Null,
        "glyph_icon"        => Null,
        "value"             => "100%",
        "chart"             => array(),
        "pie"               => array(),
    );

    //====================================================================//
    // Define Standard Options for this Widget Block
    // Uncomment to override défault options
    static $OPTIONS       = array(
//        'Width'             => "col-xs-6 col-sm-6 col-md-3 col-lg-3",
        'Width'             => "col-sm-12 col-md-12 col-lg-12",
        "AllowHtml"         => False,    
        "Separator"         => False,
    );

        
    /**
     * @var string
     */
    protected $type = "SparkInfoBlock";
    
    /**
     * Set Block Contents
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
        //  Import Value
        if ( !empty($Contents["value"]) ){
            $this->setValue($Contents["value"]);
        }         
        //==============================================================================
        //  Import Fa Icon
        if ( !empty($Contents["fa_icon"]) ){
            $this->setFaIcon($Contents["fa_icon"]);
        }         
        //==============================================================================
        //  Import Glyph Icon
        if ( !empty($Contents["glyph_icon"]) ){
            $this->setGlyphIcon($Contents["glyph_icon"]);
        }         
        //==============================================================================
        //  Import Chart Values
        if ( !empty($Contents["chart"]) ){
            $this->setChart($Contents["chart"]);
        }            
        //==============================================================================
        //  Import Pie Values
        if ( !empty($Contents["pie"]) ){
            $this->setChart($Contents["pie"]);
        }            
     
        return $this;
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
     * Set FontAwesome Icon
     * 
     * @param   $text
     * 
     * @return  Widget
     */
    public function setFaIcon($text)
    {
        $this->data["fa_icon"]     =   $text;
        return $this;
    }
    
    /**
     * Get FontAwesome Icon
     * 
     * @return  String
     */
    public function getFaIcon()
    {
        return $this->data["fa_icon"];
    }   
    
    /**
     * Set Glyph Icon
     * 
     * @param   $text
     * 
     * @return  Widget
     */
    public function setGlyphIcon($text)
    {
        $this->data["glyph_icon"]     =   $text;
        return $this;
    }
    
    /**
     * Get Glyph Icon
     * 
     * @return  String
     */
    public function getGlyphIcon()
    {
        return $this->data["glyph_icon"];
    }  
    
    /**
     * Set Value
     * 
     * @param   $text
     * 
     * @return  Widget
     */
    public function setValue($text)
    {
        $this->data["value"]     =   $text;
        return $this;
    }
    
    /**
     * Get Value
     * 
     * @return  String
     */
    public function getValue()
    {
        return $this->data["value"];
    }     
    
    /**
     * Set Chart
     * 
     * @param   $array
     * 
     * @return  Widget
     */
    public function setChart($array)
    {
        $this->data["chart"]     =   $array;
        return $this;
    }
    
    /**
     * Get Chart
     * 
     * @return  Array
     */
    public function getChart()
    {
        return $this->data["chart"];
    }       
    
    /**
     * Set Pie
     * 
     * @param   $array
     * 
     * @return  Widget
     */
    public function setPie($array)
    {
        $this->data["pie"]     =   $array;
        return $this;
    }
    
    /**
     * Get Pie
     * 
     * @return  Array
     */
    public function getPie()
    {
        return $this->data["pie"];
    } 
    
    /**
     * Set Separator
     * 
     * @param   $bool
     * 
     * @return  Widget
     */
    public function setSeparator($bool)
    {
        $this->options["Separator"]     =   $bool;
        return $this;
    }    
}
