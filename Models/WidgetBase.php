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

use Splash\Widgets\Models\Traits\DefinitionTrait;
use Splash\Widgets\Models\Traits\AccessTrait;
use Splash\Widgets\Models\Traits\ActionsTrait;
use Splash\Widgets\Models\Traits\LifecycleTrait;
use Splash\Widgets\Models\Traits\OptionsTrait;
use Splash\Widgets\Models\Traits\PositionTrait;

/**
 * Widget Model 
 * 
 * @author Bernard Paquier <pro@bernard-paquier.fr>
 */
class WidgetBase
{
    
    use AccessTrait;
    use DefinitionTrait;
    use ActionsTrait;
    use LifecycleTrait;
    use OptionsTrait;
    use PositionTrait;
    
    //====================================================================//
    // *******************************************************************//
    //  Variables Definition
    // *******************************************************************//
    //====================================================================//
    
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
        $this->setOptions();
        $this->blocks = new ArrayCollection();
    }    

    //====================================================================//
    // *******************************************************************//
    //  Widget Data Operations
    // *******************************************************************//
    //====================================================================//
    
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
