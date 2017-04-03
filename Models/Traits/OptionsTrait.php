<?php

/*
 * This file is part of the Splash Sync project.
 *
 * (c) Bernard Paquier <pro@bernard-paquier.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Splash\Widgets\Models\Traits;

use Doctrine\ORM\Mapping                        as ORM;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;

/**
 * @abstract Widget Options Trait 
 * 
 * @author Bernard Paquier <pro@bernard-paquier.fr>
 */
trait OptionsTrait
{
    //==============================================================================
    //      Constants  
    //==============================================================================

    static $WIDTH_XS       = "col-sm-6 col-md-4 col-lg-3";
    static $WIDTH_SM       = "col-sm-6 col-md-6 col-lg-4";
    static $WIDTH_DEFAULT  = "col-sm-12 col-md-6 col-lg-6";
    static $WIDTH_M        = "col-sm-12 col-md-6 col-lg-6";
    static $WIDTH_L        = "col-sm-12 col-md-6 col-lg-8";
    static $WIDTH_XL       = "col-sm-12 col-md-12 col-lg-12";
    
    //==============================================================================
    //      Variables  
    //==============================================================================
    
    /**
     * @abstract    Widget Options Array
     * @var         array
     * @ORM\Column(name="Options", type="array")
     */
    protected $options;    
    
    //==============================================================================
    //      Data Operations  
    //==============================================================================
    
    /**
     * Set Width 
     * 
     * @param   $width
     * 
     * @return  Widget
     */
    public function setWidth($width)
    {
        if (empty($width)) {
            $this->options["Width"]     =   static::$WIDTH_DEFAULT;
            return $this;
        } 
        
        switch ($width)
        {
            case "xs":
                $this->options["Width"]     =   static::$WIDTH_XS;
                break;
            case "sm":
                $this->options["Width"]     =   static::$WIDTH_SM;
                break;
            case "m":
                $this->options["Width"]     =   static::$WIDTH_M;
                break;
            case "l":
                $this->options["Width"]     =   static::$WIDTH_L;
                break;
            case "xl":
                $this->options["Width"]     =   static::$WIDTH_XL;
                break;
            default :
                $this->options["Width"]     =   $width;
                break;
        }
        
        return $this;
    }
    
    /**
     * Set Header Status
     * 
     * @param   bool $state
     * 
     * @return  Widget
     */
    public function setHeader(bool $state = True)
    {
        $this->options["Header"]     =   $state;
        return $this;
    }    
    
    /**
     * Set Footer Status
     * 
     * @param   bool $state
     * 
     * @return  Widget
     */
    public function setFooter(bool $state = True)
    {
        $this->options["Footer"]     =   $state;
        return $this;
    }    
    
    //==============================================================================
    //      Getters & Setters  
    //==============================================================================
    
    /**
     * Set Widget Options
     *
     * @param array $options        User Defined Options
     *
     * @return self
     */
    public function setOptions($options = Null)
    {
        //==============================================================================
        //  Check Options is ArrayObject
        if ( is_a($options, "ArrayObject") ) {
            $options = $options->getArrayCopy();
        }             
        //==============================================================================
        //  Check Options Array not Empty or Not an Array
        if ( empty($options) || !is_array($options) ) {
            $this->options  =   $this->getDefaultOptions();
            return $this;
        }         
        //==============================================================================
        //  Init Options Array using OptionResolver
        $resolver = new OptionsResolver();
        //==============================================================================
        //  Configure OptionResolver
        $resolver->setDefaults( $this->getDefaultOptions() );
        //==============================================================================
        //  Update Options Array using OptionResolver        
        try {
            $this->options  =   $resolver->resolve($options);
        } catch (UndefinedOptionsException $ex) {
            $this->options  =   $this->getDefaultOptions();
        } catch (InvalidOptionsException $ex) {
            $this->options  =   $this->getDefaultOptions();
        }
        return $this;
    }    
    
    /**
     * Get Widget Options
     * 
     * @return  Array
     */
    public function getOptions()
    {
        return $this->options;
    }     
    
    /**
     * Get Widget Defaults Options
     * 
     * @return  Array
     */
    public function getDefaultOptions()
    {
        return array(
            'Width'         =>  static::$WIDTH_DEFAULT,
            'Header'        =>  True,
            'Footer'        =>  True,
            'DatePreset'    =>  "M",
        );    
    }     
    
}
