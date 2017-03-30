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
use Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;

/**
 * Widget Content Model 
 * 
 * @author Bernard Paquier <pro@bernard-paquier.fr>
 */
class BaseBlock
{
    //====================================================================//
    // *******************************************************************//
    //  BLOCK GENERICS PARAMETERS
    // *******************************************************************//
    //====================================================================//
    
    const SIZE_XS       = "col-sm-6 col-md-4 col-lg-3";
    const SIZE_SM       = "col-sm-6 col-md-6 col-lg-4";
    const SIZE_DEFAULT  = "col-sm-12 col-md-12 col-lg-12";
    const SIZE_M        = "col-sm-12 col-md-6 col-lg-6";
    const SIZE_L        = "col-sm-12 col-md-6 col-lg-8";
    const SIZE_XL       = "col-sm-12 col-md-12 col-lg-12";
    
    //====================================================================//
    // Define Standard Data Fields for this Widget Block
    static $DATA          = array(
        
    );

    //====================================================================//
    // Define Standard Options for this Widget Block
    // Uncomment to override défault options
    static $OPTIONS       = array(
            'Width'          =>      self::SIZE_DEFAULT
    );
    
    //====================================================================//
    // *******************************************************************//
    //  Variables Definition
    // *******************************************************************//
    //====================================================================//
    
    /**
     * @var string
     */
    protected $type = Null;
    
    /**
     * @var array
     */
    protected $options;
    
    /**
     * @var array
     */
    protected $data;
    
    
    public function __construct() {
        $this->data     = static::$DATA;
        $this->options  = static::$OPTIONS;
    }
    
    //====================================================================//
    // *******************************************************************//
    //  Widget Getter & Setter Functions
    // *******************************************************************//
    //====================================================================//
    
    /**
     * Get Data
     * 
     * @return  Array
     */
    public function getType()
    {
        return $this->type;
    }     
    
    /**
     * Set Data
     * 
     * @param   $data
     * 
     * @return  WidgetBlock
     */
    public function setData($data)
    {
        //==============================================================================
        //  Safety Check
        if ( is_a($data, "ArrayObject") ){
            $data   =  $data->getArrayCopy();
        } 
        //==============================================================================
        //  Check Data Array not Empty or Not an Array
        if ( empty($data) || !is_array($data) ) {
            $this->data  =   static::$DATA;    
            return $this;
        }         
        //==============================================================================
        //  Init Data Array using OptionResolver
        $resolver = new OptionsResolver();
        //==============================================================================
        //  Configure OptionResolver
        $resolver->setDefaults( static::$DATA );
        //==============================================================================
        //  Update Options Array using OptionResolver        
        try {
            $this->data  =   $resolver->resolve($data);
        } catch (UndefinedOptionsException $ex) {
            $this->data  =   static::$DATA;
        } catch (InvalidOptionsException $ex) {
            $this->data  =   static::$DATA;
        }     
        return $this;
    }

    /**
     * Get Data
     * 
     * @return  Array
     */
    public function getData()
    {
        return $this->data;
    } 

    /**
     * Set Widget Contents Block Options
     *
     * @param array $options        User Defined Options
     *
     * @return self
     */
    public function setOptions($options = Null)
    {
        //==============================================================================
        //  Safety Check
        if ( is_a($options, "ArrayObject") ){
            $options   =  $options->getArrayCopy();
        }         
        //==============================================================================
        //  Check Options Array not Empty or Not an Array
        if ( empty($options) || !is_array($options) ) {
            $this->options  =   static::$OPTIONS;
            return $this;
        }         
        //==============================================================================
        //  Init Options Array using OptionResolver
        $resolver = new OptionsResolver();
        //==============================================================================
        //  Configure OptionResolver
        $resolver->setDefaults( static::$OPTIONS );
        //==============================================================================
        //  Update Options Array using OptionResolver        
        try {
            $this->options  =   $resolver->resolve($options);
        } catch (UndefinedOptionsException $ex) {
            $this->options  =   static::$OPTIONS;
        } catch (InvalidOptionsException $ex) {
            $this->options  =   static::$OPTIONS;
        }
        return $this;
    }    
    
    /**
     * Get Widget Contents Block Options
     * 
     * @return  Array
     */
    public function getOptions()
    {
        return $this->options;
    } 
    
    /**
     * Set Width 
     * 
     * @param   $width
     * @return  WidgetBlock
     */
    public function setWidth($width = self::SIZE_DEFAULT)
    {
        switch ($width)
        {
            case "xs":
                $this->options["Width"]     =   self::SIZE_XS;
                break;
            case "sm":
                $this->options["Width"]     =   self::SIZE_SM;
                break;
            case "m":
                $this->options["Width"]     =   self::SIZE_M;
                break;
            case "l":
                $this->options["Width"]     =   self::SIZE_L;
                break;
            case "xl":
                $this->options["Width"]     =   self::SIZE_XL;
                break;
            default :
                $this->options["Width"]     =   $width;
                break;
        }
        
        return $this;
    }
    
    /**
     * Check if is Block Data is Empty
     * 
     * @return  Bool
     */
    public function isEmpty()
    {
        return !empty($this->data);
    }       
    
    /**
     * @return Widget
     */
    public function end()
    {
        return $this->parent;
    }      
    
}
