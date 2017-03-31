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

use Doctrine\ORM\Mapping as ORM;

/**
 * @abstract Widget Access Trait - Defin access to a Widget 
 * 
 * @author Bernard Paquier <pro@bernard-paquier.fr>
 */
trait WidgetAccessTrait
{
    
    /**
     * @abstract    Widget Provider Service Name
     * @var         string
     * @ORM\Column(name="service", type="string", length=250)
     */
    protected $service   =   Null;
    
    /**
     * @abstract    Widget Type Name
     * @var         string
     * @ORM\Column(name="type", type="string", length=250)
     */
    protected $type;

    /**
     * @abstract    Widget Definition Extras
     * @var         array
     * @ORM\Column(name="Settings", type="array")
     */
    protected $extras;    
    
    //====================================================================//
    // *******************************************************************//
    //  Widget Getter & Setter Functions
    // *******************************************************************//
    //====================================================================//
    
    /**
     * Set Service Name
     * 
     * @param   $service
     * 
     * @return  WidgetAccessTrait
     */
    public function setService($service)
    {
        $this->service = $service;
        return $this;
    }
    
    /**
     * Get Service Name
     * 
     * @return  String
     */
    public function getService()
    {
        return $this->service;
    } 
    
    
    /**
     * Set Widget Type
     *
     * @param string $type
     *
     * @return  WidgetAccessTrait
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get Widget Type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set Widget Extra Parameters
     *
     * @param array $extras
     *
     * @return  WidgetAccessTrait
     */
    public function setExtras($extras)
    {
        $this->extras = $extras;

        return $this;
    }

    /**
     * Get Widget Extra Parameters
     *
     * @return string
     */
    public function getExtras()
    {
        return $this->extras;
    }    
    
}
