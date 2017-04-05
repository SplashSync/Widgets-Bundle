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
class BarChartBlock extends BaseBlock
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
//        'Width'             => "col-xs-6 col-sm-6 col-md-3 col-lg-3",
        'Width'             => "col-sm-12 col-md-12 col-lg-12",
        "AllowHtml"         => False,    
        "Separator"         => False,
    );

        
    /**
     * @var string
     */
    protected $type = "BarChartBlock";
    
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
        //  Import Values
        if ( !empty($Contents["values"]) ){
            $this->setValues($Contents["values"]);
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
    
}
