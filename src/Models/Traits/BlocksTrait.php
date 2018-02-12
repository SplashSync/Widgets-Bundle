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

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @abstract Widget Blocks Collection Trait 
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
    
}
