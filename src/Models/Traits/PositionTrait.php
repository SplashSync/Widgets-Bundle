<?php

/*
 *  This file is part of SplashSync Project.
 *
 *  Copyright (C) 2015-2020 Splash Sync  <www.splashsync.com>
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Splash\Widgets\Models\Traits;

use Doctrine\ORM\Mapping                        as ORM;

/**
 * Widget Position Trait
 *
 * @author Bernard Paquier <pro@bernard-paquier.fr>
 */
trait PositionTrait
{
    /**
     * @var int
     *
     * @ORM\Column(name="position", type="integer", nullable=TRUE)
     */
    protected $position;

    //==============================================================================
    //      Getters & Setters
    //==============================================================================

    /**
     * Set Position
     *
     * @param int $position
     *
     * @return self
     */
    public function setPosition(int $position) : self
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get sortorder
     *
     * @return int
     */
    public function getPosition() : int
    {
        return $this->position;
    }
}
