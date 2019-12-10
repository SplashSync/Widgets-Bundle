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
 * Morris Js Donut Chart Block Model
 */
class MorrisDonutBlock extends BaseBlock
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
        "title" => "Title",
        "dataset" => array(),
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
        ),
    );

    /**
     * @var string
     */
    protected $type = "MorrisDonutBlock";

    /**
     * Set Block Contents from Array
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
        //  Import Data
        if (!empty($contents["data"])) {
            $this->setDataSet($contents["data"]);
        }
        //==============================================================================
        //  Import Data
        if (!empty($contents["dataset"])) {
            $this->setDataSet($contents["dataset"]);
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
     * Set Chart Data
     *
     * @param array $data
     *
     * @return $this
     */
    public function setDataSet(array $data) : self
    {
        $this->data["dataset"] = $this->filterArray($data);

        return $this;
    }

    /**
     * Get Data
     *
     * @return array
     */
    public function getDataSet() : array
    {
        return $this->data["dataset"];
    }

    /**
     * Set Chart Options
     *
     * @param array $options
     *
     * @return $this
     */
    public function setChartOptions(array $options) : self
    {
        $this->options["ChartOptions"] = $options;

        return $this;
    }

    /**
     * Parse Array Data for Morris Charts
     *
     * @param array|ArrayObject $data
     *
     * @return array
     */
    protected function filterArray($data) : array
    {
        if ($data instanceof ArrayObject) {
            return array_values($data->getArrayCopy());
        }

        return array_values($data);
    }
}
