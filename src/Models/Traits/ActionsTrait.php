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

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Splash\Widgets\Entity\WidgetCollection;

/**
 * Widget Actions Trait - Define Widget Actions Js Functions
 */
trait ActionsTrait
{
    /**
     * @var array
     */
    protected $actions = array(
        "insert" => "OWReports_InsertWidget",
        "delete" => "OWReports_DeleteWidget",
        "update_parameters" => "OWReports_UpdateWidgetParameters",
        "update_options" => "OWReports_UpdateWidgetOptions",
    );

    /**
     * @var WidgetCollection
     *
     * @ORM\ManyToOne(targetEntity="Splash\Widgets\Entity\WidgetCollection", inversedBy="widgets")
     */
    protected $parent;

    //====================================================================//
    // *******************************************************************//
    //  Widget Getter & Setter Functions
    // *******************************************************************//
    //====================================================================//

    /**
     * Set Widget Actions JS Functions
     *
     * @param string $type
     * @param string $function
     *
     * @return $this
     */
    public function setAction(string $type, string $function) : self
    {
        if (isset($this->actions[$type])) {
            $this->actions[$type] = $function;
        }

        return $this;
    }

    /**
     * Get Widget Action JS Function
     *
     * @param string $type
     *
     * @return null|String
     */
    public function getAction(string $type) : ?string
    {
        if (isset($this->actions[$type])) {
            return $this->actions[$type];
        }

        return null;
    }

    /**
     * Get Widget Actions JS Functions
     *
     * @return array
     */
    public function getActions() : array
    {
        return $this->actions;
    }

    /**
     * Set Widget Parent Collection
     *
     * @param WidgetCollection $parent
     *
     * @return $this
     */
    public function setParent($parent) : self
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get Widget Parent Collection
     *
     * @return null|WidgetCollection
     */
    public function getParent() : ?WidgetCollection
    {
        return $this->parent;
    }
}
