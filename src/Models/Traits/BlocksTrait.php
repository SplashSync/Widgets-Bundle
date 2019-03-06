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

use Doctrine\Common\Collections\ArrayCollection;
use Splash\Widgets\Models\Blocks\BaseBlock;

/**
 * Widget Blocks Collection Trait
 *
 * @author Bernard Paquier <pro@bernard-paquier.fr>
 */
trait BlocksTrait
{
    //==============================================================================
    //      Variables
    //==============================================================================

    /**
     * @var ArrayCollection
     */
    protected $blocks;

    //==============================================================================
    //      Getters & Setters
    //==============================================================================

    /**
     * Add Widget Block
     *
     * @param BaseBlock $block
     *
     * @return $this
     */
    public function addBlock(BaseBlock $block) : self
    {
        $this->blocks[] = $block;

        return $this;
    }

    /**
     * Remove Widget Block
     *
     * @param BaseBlock $block
     *
     * @return $this
     */
    public function removeBlock(BaseBlock $block) : self
    {
        $this->blocks->removeElement($block);

        return $this;
    }

    /**
     * Get Widget Blocks
     *
     * @return ArrayCollection
     */
    public function getBlocks() : ArrayCollection
    {
        return $this->blocks;
    }
}
