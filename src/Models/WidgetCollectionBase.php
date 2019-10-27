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

namespace Splash\Widgets\Models;

use Doctrine\ORM\Mapping                        as ORM;

/**
 * Widgets Collection Base Object
 */
class WidgetCollectionBase
{
    //==============================================================================
    //      Definition
    //==============================================================================

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=TRUE)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    protected $type;

    /**
     * @var string
     *
     * @ORM\Column(name="preset", type="string", length=255, nullable=TRUE)
     */
    protected $preset = "M";

    //==============================================================================
    //      DATA OPERATIONS
    //==============================================================================

    /**
     * Magic Getter to String
     *
     * @return string
     */
    public function __toString() : string
    {
        return $this->getName();
    }

    //==============================================================================
    //      GETTERS & SETTERS
    //==============================================================================

    /**
     * Set name
     *
     * @param string $name
     *
     * @return $this
     */
    public function setName(string $name) : self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName() : string
    {
        return (string) $this->name;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return $this
     */
    public function setType($type) : self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType() : string
    {
        return $this->type;
    }

    /**
     * Set Preset
     *
     * @param string $preset
     *
     * @return $this
     */
    public function setPreset($preset) : self
    {
        $this->preset = $preset;

        return $this;
    }

    /**
     * Get Preset
     *
     * @return string
     */
    public function getPreset() : string
    {
        return $this->preset;
    }
}
