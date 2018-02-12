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
 * @abstract Widget Position Trait 
 * 
 * @author Bernard Paquier <pro@bernard-paquier.fr>
 */
trait PositionTrait
{
    //==============================================================================
    //      Constants  
    //==============================================================================

    //==============================================================================
    //      Variables  
    //==============================================================================

    /**
     * @var integer 
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
     * @param integer $position
     *
     * @return self
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get sortorder
     *
     * @return integer
     */
    public function getPosition()
    {
        return $this->position;
    }

}
