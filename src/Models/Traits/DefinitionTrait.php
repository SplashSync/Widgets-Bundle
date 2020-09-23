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

/**
 * Widget Definition Trait
 *
 * @author Bernard Paquier <pro@bernard-paquier.fr>
 */
trait DefinitionTrait
{
    /**
     * Widget Human Readable Name
     *
     * @var string
     */
    protected $name;

    /**
     * Widget Human Readable Description
     *
     * @var string
     */
    protected $description;

    /**
     * Widget Header Title
     *
     * @var string
     */
    protected $title;

    /**
     * Widget Header Sub-Title
     *
     * @var string
     */
    protected $subtitle;

    /**
     * Widget Header Icon
     *
     * @var string
     */
    protected $icon;

    /**
     * Widget Source/Origin
     *
     * @var string
     */
    protected $origin;

    //====================================================================//
    // *******************************************************************//
    //  Widget Getter & Setter Functions
    // *******************************************************************//
    //====================================================================//

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
     * Set description
     *
     * @param string $description
     *
     * @return $this
     */
    public function setDescription(string $description) : self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription() : string
    {
        return (string) $this->description;
    }

    /**
     * Set Title
     *
     * @param string $title
     *
     * @return $this
     */
    public function setTitle(string $title) : self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get Title
     *
     * @return string
     */
    public function getTitle() : string
    {
        return (string) $this->title;
    }

    /**
     * Set SubTitle
     *
     * @param string $subtitle
     *
     * @return $this
     */
    public function setSubTitle(string $subtitle) : self
    {
        $this->subtitle = $subtitle;

        return $this;
    }

    /**
     * Get SubTitle
     *
     * @return string
     */
    public function getSubTitle() : string
    {
        return (string) $this->subtitle;
    }

    /**
     * Set Icon
     *
     * @param string $text
     *
     * @return $this
     */
    public function setIcon(string $text) : self
    {
        $this->icon = $text;

        return $this;
    }

    /**
     * Get Icon
     *
     * @return string
     */
    public function getIcon() : string
    {
        return (string) $this->icon;
    }

    /**
     * Set Origin
     *
     * @param string $origin
     *
     * @return $this
     */
    public function setOrigin($origin) : self
    {
        $this->origin = $origin;

        return $this;
    }

    /**
     * Get Origin
     *
     * @return string
     */
    public function getOrigin() : string
    {
        return (string) $this->origin;
    }
}
