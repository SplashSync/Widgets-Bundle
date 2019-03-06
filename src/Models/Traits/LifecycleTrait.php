<?php

/*
 *  This file is part of SplashSync Project.
 *
 *  Copyright (C) 2015-2019 Splash Sync  <www.splashsync.com>
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Splash\Widgets\Models\Traits;

use DateTime;
use Doctrine\ORM\Mapping                        as ORM;

/**
 * Widget Lifecycle Trait
 *
 * @author Bernard Paquier <pro@bernard-paquier.fr>
 */
trait LifecycleTrait
{
    //==============================================================================
    //      Variables
    //==============================================================================

    /**
     * @var DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime")
     */
    protected $createdAt;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="updatedAt", type="datetime")
     */
    protected $updatedAt;

    /**
     * @var null|DateTime
     */
    protected $refreshAt;

    //==============================================================================
    //      LIFECYCLES FUNCTIONS
    //==============================================================================

    /** @ORM\PrePersist() */
    public function prePersist()
    {
        $now = new DateTime();
        //====================================================================//
        // Set Dates
        $this->setCreatedAt($now);
        $this->setUpdatedAt($now);
    }

    /** @ORM\PreUpdate() */
    public function preUpdate()
    {
        $now = new DateTime();
        //====================================================================//
        // Set Dates
        $this->setUpdatedAt($now);
    }

    //==============================================================================
    //      Getters & Setters
    //==============================================================================

    /**
     * Set createdAt
     *
     * @param DateTime $createdAt
     *
     * @return $this
     */
    public function setCreatedAt($createdAt) : self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return DateTime
     */
    public function getCreatedAt() : DateTime
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param DateTime $updatedAt
     *
     * @return $this
     */
    public function setUpdatedAt($updatedAt) : self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return DateTime
     */
    public function getUpdatedAt() : DateTime
    {
        return $this->updatedAt;
    }

    /**
     * Set refreshAt
     *
     * @param DateTime $refreshAt
     *
     * @return $this
     */
    public function setRefreshAt(DateTime $refreshAt = null) : self
    {
        $this->refreshAt = $refreshAt ? $refreshAt : new DateTime();

        return $this;
    }

    /**
     * Get refreshAt
     *
     * @return null|DateTime
     */
    public function getRefreshAt() : ?DateTime
    {
        return $this->refreshAt;
    }
}
