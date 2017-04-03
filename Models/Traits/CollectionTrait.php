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
use Splash\Widgets\Entity\Widget;

/**
 * @abstract Widget Collection Trait 
 * 
 * @author Bernard Paquier <pro@bernard-paquier.fr>
 */
trait CollectionTrait
{
    static $SERVICE    =   "Splash.Widgets.Collection";

    //==============================================================================
    //      Constants  
    //==============================================================================

    //==============================================================================
    //      Variables  
    //==============================================================================

    /**
     * @ORM\OneToMany(targetEntity="Splash\Widgets\Entity\Widget", mappedBy="parent", cascade="all" )
     * @ORM\OrderBy({"position" = "ASC"})
     */
    protected $widgets;
    
    //==============================================================================
    //      Getters & Setters  
    //==============================================================================

    /**
     * Get Service Name
     * 
     * @return  String
     */
    public function getService()
    {
        return static::$SERVICE;
    }
    
    /**
     * Add Widget
     *
     * @param   Widget  $widget
     *
     * @return Report
     */
    public function addWidget(Widget $widget)
    {
        //==============================================================================
        //      Setup Widget  
        $widget->setParent($this);
        $widget->setPosition($this->widgets->count());
                
        $this->widgets[] = $widget;

        return $this;
    }    
    
    /**
     * Get All Widgets
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getWidgets()
    {
        return $this->widgets;
    }

    /**
     * Get an Widget by Type
     *
     * @param   string      $Type        Widget Identifier
     * 
     * @return  Widget
     */
    public function getWidget($Type)
    {
        foreach ($this->widgets as $Widget ) {
            if ( $Widget->getId() == $Type ) {
                return $Widget;
            }
        }        
        return Null;
    } 

    /**
     * Remove Widget
     *
     * @param Widget $Widget
     */
    public function removeWidget(Widget $Widget)
    {
        $this->widgets->removeElement($Widget);
    }
    
    /**
     * Re-Order Widgets using their Id
     * 
     * @param array $OrderArray         Array of Item Ids
     * 
     * @return Report
     */
    public function reorder($OrderArray)
    {
        //==============================================================================
        // Safety Check of Input Value
        if ( !is_array($OrderArray) || empty($OrderArray)) {
            return False;
        }
        //==============================================================================
        // Check Widget Count is Similar
        if ( count($OrderArray) !== $this->getWidgets()->count()) {
            return False;
        }        
        
        //==============================================================================
        // Re-Order Items
        foreach ($OrderArray as $Index => $Id) {
            $Widget = $this->getWidget($Id);
            if ( $Widget ) {
                $Widget->setPosition($Index);
            }
        }
        return True;
    }
    
    
    

}
