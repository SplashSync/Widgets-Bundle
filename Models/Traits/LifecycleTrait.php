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

/**
 * @abstract Widget Lifecycles Trait 
 * 
 * @author Bernard Paquier <pro@bernard-paquier.fr>
 */
trait LifecycleTrait
{
    //==============================================================================
    //      Variables  
    //==============================================================================

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime")
     */
    protected $createdAt;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updatedAt", type="datetime")
     */
    protected $updatedAt;
    
    //==============================================================================
    //      LIFECYCLES FUNCTIONS  
    //==============================================================================       
    
    /** @ORM\PrePersist() */    
    public function prePersist()
    {
        //====================================================================//
        // Set Dates
        $this->setCreatedAt(new \DateTime);
        $this->setUpdatedAt(new \DateTime);
    }
    
    /** @ORM\PreUpdate() */    
    public function preUpdate()
    {
        //====================================================================//
        // Set Dates
        $this->setUpdatedAt(new \DateTime);
    }    
    
    //==============================================================================
    //      Getters & Setters  
    //==============================================================================

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Report
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Report
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
    
    
}
