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

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @abstract Widget Access Trait - Defin access to a Widget 
 * 
 * @author Bernard Paquier <pro@bernard-paquier.fr>
 */
trait WidgetAccessTrait
{
    
    /**
     * @var string
     */
    protected $service   =   Null;
    
    /**
     * @var string
     */
    protected $type;

    /**
     * @var array
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
