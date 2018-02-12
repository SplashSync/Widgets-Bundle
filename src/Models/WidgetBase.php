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

use Doctrine\Common\Collections\ArrayCollection;

use Splash\Widgets\Models\Traits\AccessTrait;
use Splash\Widgets\Models\Traits\ActionsTrait;
use Splash\Widgets\Models\Traits\BlocksTrait;
use Splash\Widgets\Models\Traits\DefinitionTrait;
use Splash\Widgets\Models\Traits\LifecycleTrait;
use Splash\Widgets\Models\Traits\OptionsTrait;
use Splash\Widgets\Models\Traits\ParametersTrait;
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
    use BlocksTrait;
    use LifecycleTrait;
    use OptionsTrait;
    use ParametersTrait;
    use PositionTrait;
    
    //====================================================================//
    // *******************************************************************//
    //  Variables Definition
    // *******************************************************************//
    //====================================================================//
    
    public function __construct()
    {
        $this->setRefreshAt();
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
                $this->setRefreshAt($Contents["date"]);
            } else if ( is_scalar($Contents["date"]) ){
                $this->setRefreshAt(new \DateTime($Contents["date"]));
            }
        } 
        return $this;
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
        
}
