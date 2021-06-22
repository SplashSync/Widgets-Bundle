<?php

/*
 *  Copyright (C) 2021 BadPixxel <www.badpixxel.com>
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Splash\Widgets\Models;

use ArrayObject;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Exception;
use Splash\Widgets\Models\Traits\AccessTrait;
use Splash\Widgets\Models\Traits\ActionsTrait;
use Splash\Widgets\Models\Traits\BlocksTrait;
use Splash\Widgets\Models\Traits\DefinitionTrait;
use Splash\Widgets\Models\Traits\LifecycleTrait;
use Splash\Widgets\Models\Traits\OptionsTrait;
use Splash\Widgets\Models\Traits\ParametersTrait;
use Splash\Widgets\Models\Traits\PositionTrait;

/**
 * Widget Model
 *
 * @author Bernard Paquier <pro@bernard-paquier.fr>
 */
class WidgetBase
{
    use AccessTrait;
    use DefinitionTrait;
    use ActionsTrait;
    use BlocksTrait;
    use LifecycleTrait;
    use OptionsTrait;
    use ParametersTrait;
    use PositionTrait;

    //==============================================================================
    //      CONSTRUCTOR
    //==============================================================================

    /**
     * Class Constructor
     */
    public function __construct()
    {
        $this->setRefreshAt();
        $this->setOptions();
        $this->blocks = new ArrayCollection();
    }

    //====================================================================//
    //  Widget Getter & Setter Functions
    //====================================================================//

    /**
     * Magic Getter to Access Parameters
     *
     * @param string $key
     *
     * @return null|mixed
     */
    public function __get(string $key)
    {
        if (array_key_exists($key, $this->parameters)) {
            return $this->parameters[$key];
        }

        return null;
    }

    /**
     * Magic Setter to Update Parameters
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return $this
     */
    public function __set(string $key, $value) : self
    {
        $this->parameters[$key] = $value;

        return $this;
    }

    //====================================================================//
    //  Widget Data Operations
    //====================================================================//

    /**
     * Set Widget Contents
     *
     * @param null|array|ArrayObject $contents
     *
     * @return $this
     */
    public function setContents($contents) : self
    {
        //==============================================================================
        //  Safety Check
        if (!is_array($contents) && !($contents instanceof ArrayObject)) {
            return $this;
        }
        //==============================================================================
        //  Import Main
        $this->importMainContents($contents);
        //==============================================================================
        //  Import Date
        $this->importDateContents($contents);

        return $this;
    }

    /**
     * Import Widget Main Contents
     *
     * @param array|ArrayObject $contents
     */
    private function importMainContents($contents) : void
    {
        //==============================================================================
        //  Import Title
        if (!empty($contents["title"])) {
            $this->setTitle($contents["title"]);
        }
        //==============================================================================
        //  Import SubTitle
        if (!empty($contents["subtitle"])) {
            $this->setSubTitle($contents["subtitle"]);
        }
        //==============================================================================
        //  Import Icon
        if (!empty($contents["icon"])) {
            $this->setIcon($contents["icon"]);
        }
        //==============================================================================
        //  Import Origin
        if (!empty($contents["origin"])) {
            $this->setOrigin($contents["origin"]);
        }
    }

    /**
     * Import Widget Date Contents
     *
     * @param array|ArrayObject $contents
     *
     * @throws Exception
     */
    private function importDateContents($contents) : void
    {
        //==============================================================================
        //  Import Date
        if (!empty($contents["date"])) {
            if ($contents["date"] instanceof DateTime) {
                $this->setRefreshAt($contents["date"]);
            } elseif (is_scalar($contents["date"])) {
                $this->setRefreshAt(new DateTime((string) $contents["date"]));
            }
        }
    }
}
