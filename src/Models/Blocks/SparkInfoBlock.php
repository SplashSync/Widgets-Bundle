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

namespace Splash\Widgets\Models\Blocks;

use ArrayObject;

/**
 * Widget SparkInfo Block
 * Render a Simple Spark Info (i.e. Ico + Number) Block
 */
class SparkInfoBlock extends BaseBlock
{
    //====================================================================//
    // *******************************************************************//
    //  BLOCK GENERICS PARAMETERS
    // *******************************************************************//
    //====================================================================//

    /**
     * Define Standard Data Fields for this Widget Block
     *
     * @var array
     */
    public static $DATA = array(
        "color" => "defaut",
        "title" => "Title",
        "fa_icon" => null,
        "glyph_icon" => null,
        "value" => "100%",
        "chart" => array(),
        "pie" => array(),
    );

    /**
     * Define Standard Options for this Widget Block
     * Uncomment to override défault options
     *
     * @var array
     */
    public static $OPTIONS = array(
        'Width' => "col-sm-12 col-md-12 col-lg-12",
        "AllowHtml" => false,
        "ChartOptions" => array(
            "height" => "60",
            "barwidth" => "10",
            "piesize" => "60",
        ),
    );

    /**
     * @var string
     */
    protected $type = "SparkInfoBlock";

    /**
     * Set Block Contents
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
        //  Import Title
        if (!empty($contents["title"])) {
            $this->setTitle($contents["title"]);
        }
        //==============================================================================
        //  Import Value
        if (!empty($contents["value"])) {
            $this->setValue($contents["value"]);
        }
        //==============================================================================
        //  Import Fa Icon
        if (!empty($contents["fa_icon"])) {
            $this->setFaIcon($contents["fa_icon"]);
        }
        //==============================================================================
        //  Import Glyph Icon
        if (!empty($contents["glyph_icon"])) {
            $this->setGlyphIcon($contents["glyph_icon"]);
        }
        //==============================================================================
        //  Import Chart Values
        if (!empty($contents["chart"])) {
            $this->setChart($contents["chart"]);
        }
        //==============================================================================
        //  Import Pie Values
        if (!empty($contents["pie"])) {
            $this->setChart($contents["pie"]);
        }

        return $this;
    }

    //====================================================================//
    // *******************************************************************//
    //  Block Getter & Setter Functions
    // *******************************************************************//
    //====================================================================//

    /**
     * Set Title
     *
     * @param string $text
     *
     * @return $this
     */
    public function setTitle(string $text) : self
    {
        $this->data["title"] = $text;

        return $this;
    }

    /**
     * Get Title
     *
     * @return String
     */
    public function getTitle() : string
    {
        return $this->data["title"];
    }

    /**
     * Set FontAwesome Icon
     *
     * @param string $text
     *
     * @return $this
     */
    public function setFaIcon(string $text) : self
    {
        $this->data["fa_icon"] = $text;

        return $this;
    }

    /**
     * Get FontAwesome Icon
     *
     * @return null|string
     */
    public function getFaIcon() : ?string
    {
        return $this->data["fa_icon"];
    }

    /**
     * Set Glyph Icon
     *
     * @param string $text
     *
     * @return $this
     */
    public function setGlyphIcon(string $text) : self
    {
        $this->data["glyph_icon"] = $text;

        return $this;
    }

    /**
     * Get Glyph Icon
     *
     * @return null|string
     */
    public function getGlyphIcon() : ?string
    {
        return $this->data["glyph_icon"];
    }

    /**
     * Set Value
     *
     * @param string $text
     *
     * @return $this
     */
    public function setValue(string $text) : self
    {
        $this->data["value"] = $text;

        return $this;
    }

    /**
     * Get Value
     *
     * @return String
     */
    public function getValue() : string
    {
        return $this->data["value"];
    }

    /**
     * Set Chart
     *
     * @param array $array
     *
     * @return $this
     */
    public function setChart(array $array) : self
    {
        $this->data["chart"] = $array;

        return $this;
    }

    /**
     * Get Chart
     *
     * @return Array
     */
    public function getChart() : array
    {
        return $this->data["chart"];
    }

    /**
     * Set Pie
     *
     * @param array $array
     *
     * @return $this
     */
    public function setPie(array $array) : self
    {
        $this->data["pie"] = $array;

        return $this;
    }

    /**
     * Get Pie
     *
     * @return array
     */
    public function getPie() : array
    {
        return $this->data["pie"];
    }

    /**
     * Set Separator
     *
     * @param bool $bool
     *
     * @return $this
     */
    public function setSeparator(bool $bool) : self
    {
        $this->options["Separator"] = $bool;

        return $this;
    }
}
