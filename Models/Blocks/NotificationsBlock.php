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
class NotificationsBlock extends BaseBlock
{

    //====================================================================//
    // *******************************************************************//
    //  BLOCK GENERICS PARAMETERS
    // *******************************************************************//
    //====================================================================//

    //====================================================================//
    // Define Standard Data Fields for this Widget Block
    static $DATA          = array(
        'error'                     => Null,
        'warning'                   => Null,
        'info'                      => Null,
        'success'                   => Null
    );

    //====================================================================//
    // Define Standard Options for this Widget Block
    // Uncomment to override défault options
//    static $OPTIONS       = array(
//            'Width'          =>      "col-xs-12 col-sm-12 col-md-12 col-lg-12"
//    );

        
    /**
     * @var string
     */
    protected $type = "NotificationsBlock";
    
    //====================================================================//
    // *******************************************************************//
    //  Block Getter & Setter Functions
    // *******************************************************************//
    //====================================================================//
    
    /**
     * Set Error
     * 
     * @param   $text
     * 
     * @return  Widget
     */
    public function setError($text)
    {
        $this->data["error"]       =   $text;
        return $this;
    }    

    /**
     * Get Error
     * 
     * @return  String
     */
    public function getError()
    {
        return $this->data["error"];
    }        

    /**
     * Set Warning
     * 
     * @param   $text
     * 
     * @return  Widget
     */
    public function setWarning($text)
    {
        $this->data["warning"]     =   $text;
        return $this;
    }    

    /**
     * Get Warning
     * 
     * @return  String
     */
    public function getWarning()
    {
        return $this->data["warning"];
    }     
    
    /**
     * Set Info
     * 
     * @param   $text
     * 
     * @return  Widget
     */
    public function setInfo($text)
    {
        $this->data["info"]        =   $text;
        return $this;
    }   

    /**
     * Get Info
     * 
     * @return  String
     */
    public function getInfo()
    {
        return $this->data["info"];
    }     
    
    /**
     * Set Success
     * 
     * @param   $text
     * 
     * @return  Widget
     */
    public function setSuccess($text)
    {
        $this->data["success"]      =   $text;
        return $this;
    }   

    /**
     * Get Success
     * 
     * @return  String
     */
    public function getSuccess()
    {
        return $this->data["success"];
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
        //  Import Error
        if ( !empty($Contents["error"]) ){
            $this->setError($Contents["error"]);
        } 
        //==============================================================================
        //  Import Warning
        if ( !empty($Contents["warning"]) ){
            $this->setWarning($Contents["warning"]);
        } 
        //==============================================================================
        //  Import Info
        if ( !empty($Contents["info"]) ){
            $this->setInfo($Contents["info"]);
        } 
        //==============================================================================
        //  Import Success
        if ( !empty($Contents["success"]) ){
            $this->setSuccess($Contents["success"]);
        } 
        return $this;
    }      
}
