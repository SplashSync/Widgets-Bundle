<?php

/*
 * This file is part of the Splash Sync project.
 *
 * (c) Bernard Paquier <pro@bernard-paquier.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Splash\Widgets\Models;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

use Splash\Widgets\Models\Traits\WidgetDefinitionTrait;
use Splash\Widgets\Models\Traits\WidgetAccessTrait;
use Splash\Widgets\Models\Traits\WidgetActionsTrait;

/**
 * Widget Model 
 * 
 * @author Bernard Paquier <pro@bernard-paquier.fr>
 */
class Widget
{
    
    use WidgetAccessTrait;
    use WidgetDefinitionTrait;
    use WidgetActionsTrait;
    
    //====================================================================//
    // *******************************************************************//
    //  WIDGET GENERICS PARAMETERS
    // *******************************************************************//
    //====================================================================//

    const SIZE_XS       = "col-sm-6 col-md-4 col-lg-3";
    const SIZE_SM       = "col-sm-6 col-md-6 col-lg-4";
    const SIZE_DEFAULT  = "col-sm-12 col-md-6 col-lg-6";
    const SIZE_M        = "col-sm-12 col-md-6 col-lg-6";
    const SIZE_L        = "col-sm-12 col-md-6 col-lg-8";
    const SIZE_XL       = "col-sm-12 col-md-12 col-lg-12";
    
    //====================================================================//
    // Define Standard Options for this Widget Block
    // Uncomment to override défault options
    const OPTIONS       = array(
            'Width'          =>      self::SIZE_DEFAULT,
            'Header'         =>      True,
            'Footer'         =>      True,
            'DatePreset'     =>      "M",
    );    
    
    //====================================================================//
    // *******************************************************************//
    //  Variables Definition
    // *******************************************************************//
    //====================================================================//
    
    /**
     * @var Array
     */
    protected $options      =   self::OPTIONS;
    
    /**
     * @var array
     */
    protected $parameters = array();    
    
    /**
     * @var datetime
     */
    protected $date;    
    
    /**
     * @var string
     */
    protected $origin;    
    
    /**
     * @var ArrayCollection
     */
    protected $blocks;    
    

    public function __construct()
    {
        $this->blocks = new ArrayCollection();
    }    

    //====================================================================//
    // *******************************************************************//
    //  Widget Dat Operations
    // *******************************************************************//
    //====================================================================//

    /**
     * Set Width 
     * 
     * @param   $width
     * @return  Widget
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
     * Set Widget Contents
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
        //  Import SubTitle
        if ( !empty($Contents["subtitle"]) ){
            $this->setSubTitle($Contents["subtitle"]);
        } 
        //==============================================================================
        //  Import Icon
        if ( !empty($Contents["icon"]) ){
            $this->setIcon($Contents["icon"]);
        } 
        //==============================================================================
        //  Import Origin
        if ( !empty($Contents["origin"]) ){
            $this->setOrigin($Contents["origin"]);
        } 
        //==============================================================================
        //  Import Date
        if ( !empty($Contents["date"]) ){
            if ( is_a($Contents["date"],"DateTime") ){
                $this->setDate($Contents["date"]);
            } else if ( is_scalar($Contents["date"]) ){
                $this->setDate(new \DateTime($Contents["date"]));
            }
        } 
        return $this;
    }       
    
    //====================================================================//
    // *******************************************************************//
    //  Widget Blocks Management
    // *******************************************************************//
    //====================================================================//
    
    /**
     * Add Widget Block
     *
     * @param  $block
     *
     * @return Widget
     */
    public function addBlock( $block )
    {
        $this->blocks[] = $block;

        return $this;
    }

    /**
     * Remove Widget Block
     *
     * @param $block
     */
    public function removeBlock( $block ) 
    {
        $this->blocks->removeElement($block);
    }

    /**
     * Get Widget Blocks
     *
     * @return Collection
     */
    public function getBlocks()
    {
        return $this->blocks;
    }
    
    //====================================================================//
    // *******************************************************************//
    //  Widget Getter & Setter Functions
    // *******************************************************************//
    //====================================================================//
    
    public function __get($Key)
    {
        if (array_key_exists($Key, $this->parameters)) {
            return $this->parameters[$Key];
        }
        return NUll;         
    }
    
    public function __set($Key,$Value)
    {
        $this->parameters[$Key] = $Value;
        return $this;         
    }
        
    
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
            $this->options  =   self::OPTIONS;
            return $this;
        }         
        //==============================================================================
        //  Init Options Array using OptionResolver
        $resolver = new OptionsResolver();
        //==============================================================================
        //  Configure OptionResolver
        $resolver->setDefaults( self::OPTIONS );
        //==============================================================================
        //  Update Options Array using OptionResolver        
        try {
            $this->options  =   $resolver->resolve($options);
        } catch (UndefinedOptionsException $ex) {
            $this->options  =   self::OPTIONS;
        } catch (InvalidOptionsException $ex) {
            $this->options  =   self::OPTIONS;
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
     * Set Origin
     * 
     * @param   $Origin
     * @return  Widget
     */
    public function setOrigin($Origin)
    {
        $this->origin = $Origin;
        
        return $this;
    }
    
    /**
     * Get Origin
     * 
     * @return  String
     */
    public function getOrigin()
    {
        return $this->origin;
    }
    
    /**
     * Set Date
     * 
     * @param   $Date
     * 
     * @return  Widget
     */
    public function setDate(\DateTime  $Date)
    {
        $this->date = $Date;
        
        return $this;
    }
    
    /**
     * Get Date
     * 
     * @return  String
     */
    public function getDate()
    {
        return $this->date;
    }
    
    /**
     * Set Parameters
     * 
     * @param   $parameters
     * 
     * @return  Widget
     */
    public function setParameters(array $parameters)
    {
        $this->parameters = $parameters;
        
        return $this;
    }
    
    /**
     * Get Parameters
     *
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }
}
