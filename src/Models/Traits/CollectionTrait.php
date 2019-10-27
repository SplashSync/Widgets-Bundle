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
use Doctrine\ORM\Mapping                        as ORM;
use Splash\Widgets\Entity\Widget;

/**
 * Widget Collection Trait
 *
 * @author Bernard Paquier <pro@bernard-paquier.fr>
 */
trait CollectionTrait
{
    public static $SERVICE = "splash.widgets.collection";

    //==============================================================================
    //      Variables
    //==============================================================================

    /**
     * @ORM\OneToMany(targetEntity="Splash\Widgets\Entity\Widget", mappedBy="parent", cascade="all" )
     * @ORM\OrderBy({"position" = "ASC"})
     */
    protected $widgets;

    //==============================================================================
    //      Getters & Setters
    //==============================================================================

    /**
     * Get Service Name
     *
     * @return string
     */
    public function getService() : string
    {
        return static::$SERVICE;
    }

    /**
     * Add Widget
     *
     * @param Widget $widget
     *
     * @return $this
     */
    public function addWidget(Widget $widget) : self
    {
        //==============================================================================
        //      Setup Widget
        $widget->setParent($this);
        $widget->setPosition($this->widgets->count());

        if ($this->getPreset()) {
            $widget->setParameter("DatePreset", $this->getPreset());
        }

        $this->widgets[] = $widget;

        return $this;
    }

    /**
     * Get All Widgets
     *
     * @return Collection
     */
    public function getWidgets() : Collection
    {
        return $this->widgets;
    }

    /**
     * Get an Widget by Type
     *
     * @param string $type Widget Identifier
     *
     * @return Widget
     */
    public function getWidget(string $type) : ?Widget
    {
        foreach ($this->widgets as $widget) {
            if ($widget->getId() == $type) {
                return $widget;
            }
        }

        return null;
    }

    /**
     * Remove Widget
     *
     * @param Widget $widget
     *
     * @return $this
     */
    public function removeWidget(Widget $widget) : self
    {
        $this->widgets->removeElement($widget);

        return $this;
    }

    /**
     * Re-Order Widgets using their Id
     *
     * @param array $orderArray Array of Item Ids
     *
     * @return bool
     */
    public function reorder($orderArray) : bool
    {
        //==============================================================================
        // Safety Check of Input Value
        if (!is_array($orderArray) || empty($orderArray)) {
            return false;
        }
        //==============================================================================
        // Check Widget Count is Similar
        if (count($orderArray) !== $this->getWidgets()->count()) {
            return false;
        }
        //==============================================================================
        // Re-Order Items
        foreach ($orderArray as $index => $widgetId) {
            $widget = $this->getWidget($widgetId);
            if ($widget) {
                $widget->setPosition((int) $index);
            }
        }

        return true;
    }
}
