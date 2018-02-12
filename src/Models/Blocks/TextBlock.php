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

/**
 * Abstact Widget Model 
 * 
 * @author Bernard Paquier <pro@bernard-paquier.fr>
 */
class TextBlock extends BaseBlock
{

    //====================================================================//
    // *******************************************************************//
    //  BLOCK GENERICS PARAMETERS
    // *******************************************************************//
    //====================================================================//

    //====================================================================//
    // Define Standard Data Fields for this Widget Block
    static $DATA          = array(
        "text"              => Null
    );

    //====================================================================//
    // Define Standard Options for this Widget Block
    // Uncomment to override défault options
    static $OPTIONS       = array(
        'Width'             =>      "col-xs-12 col-sm-12 col-md-12 col-lg-12",
        "AllowHtml"         =>      False,
    );

        
    /**
     * @var string
     */
    protected $type = "TextBlock";
    
    //====================================================================//
    // *******************************************************************//
    //  Block Getter & Setter Functions
    // *******************************************************************//
    //====================================================================//
    
    /**
     * Set Text
     * 
     * @param   $text
     * 
     * @return  Widget
     */
    public function setText($text)
    {
        if ( is_scalar($text) ) {
            $this->data["text"]     =   $text;
        } 
        return $this;
    }
    
    /**
     * Get Text
     * 
     * @return  String
     */
    public function getText()
    {
        return $this->data["text"];
    }        

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
        if ( !empty($Contents["text"]) ){
            $this->setText($Contents["text"]);
        } 
        return $this;
    }   
    
}
