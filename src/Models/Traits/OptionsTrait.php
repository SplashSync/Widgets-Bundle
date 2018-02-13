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

use DateTime;

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

    //==============================================================================
    //      Widgets Width  
    static $WIDTH_XS       = "col-sm-6 col-md-4 col-lg-3";
    static $WIDTH_SM       = "col-sm-6 col-md-6 col-lg-4";
    static $WIDTH_DEFAULT  = "col-sm-12 col-md-6 col-lg-6";
    static $WIDTH_M        = "col-sm-12 col-md-6 col-lg-6";
    static $WIDTH_L        = "col-sm-12 col-md-6 col-lg-8";
    static $WIDTH_XL       = "col-sm-12 col-md-12 col-lg-12";

    //==============================================================================
    //      Widgets Color 
    static $COLOR_NONE      = " ";
    static $COLOR_DEFAULT   = "panel panel-default";
    static $COLOR_PRIMARY   = "panel panel-primary";
    static $COLOR_SUCCESS   = "panel panel-success";
    static $COLOR_INFO      = "panel panel-info";
    static $COLOR_WARNING   = "panel panel-warning";
    static $COLOR_DANGER    = "panel panel-danger";
    
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
    
    /**
     * @abstract    Get Widget Max Cache Date
     * 
     * @return  \DateTime
     */
    public function getCacheMaxDate()
    {
        if ( !isset($this->options['UseCache']) || !isset($this->options['CacheLifeTime']) || !$this->options['UseCache'] ) {
            return new DateTime();
        } 
        return new DateTime($this->options['CacheLifeTime']  . "minutes");
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
        //  Update Options Array using OptionResolver 
        $this->options  =   $this->validateOptions($options);
        
        return $this;
    }    
    
    /**
     * Get Widget Options
     * 
     * @return  Array
     */
    public function getOptions()
    {
        return $this->validateOptions($this->options);
    }    
    
    /**
     * validate Widget Options
     *
     * @param array $options        User Defined Options
     *
     * @return array
     */
    public function validateOptions($options = Null) : array
    {
        //==============================================================================
        //  Check Options is ArrayObject
        if ( is_a($options, "ArrayObject") ) {
            $options = $options->getArrayCopy();
        }             
        //==============================================================================
        //  Check Options Array not Empty or Not an Array
        if ( empty($options) || !is_array($options) ) {
            return  $this->getDefaultOptions();
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
            return  $resolver->resolve($options);
        } catch (UndefinedOptionsException $ex) {
            return  $this->getDefaultOptions();
        } catch (InvalidOptionsException $ex) {
            return $this->getDefaultOptions();
        }
        
        return $this->getDefaultOptions();
    }    
    
    /**
     * Update Widget Options With Given Values
     * 
     * @param array $Options        User Defined Options
     * 
     * @return  Array
     */
    public function mergeOptions($Options = array())
    {
        return $this->setOptions(array_merge($this->getOptions(), $Options));
    }     
    
    /**
     * Get Widget Defaults Options
     * 
     * @return  Array
     */
    public static function getDefaultOptions()
    {
        return array(
            'Width'         =>  static::$WIDTH_DEFAULT,
            'Color'         =>  static::$COLOR_DEFAULT,
            'Header'        =>  True,
            'Footer'        =>  True,
            'Border'        =>  True,
            'DatePreset'    =>  "M",
            'UseCache'      =>  True,
            'CacheLifeTime' =>  10,
            'Editable'      =>  False,
            'EditMode'      =>  False,
        );    
    }     
    
}
