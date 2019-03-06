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
 * Widget Table Block
 * Render an Html Table wit Escaped or Raw Html Cells
 */
class TableBlock extends BaseBlock
{
    //====================================================================//
    // *******************************************************************//
    //  BLOCK GENERICS PARAMETERS
    // *******************************************************************//
    //====================================================================//

    //====================================================================//
    // Define Standard Data Fields for this Widget Block
    public static $DATA = array(
        "rows" => null,
    );

    //====================================================================//
    // Define Standard Options for this Widget Block
    // Uncomment to override défault options
    public static $OPTIONS = array(
        'Width' => "col-xs-12 col-sm-12 col-md-12 col-lg-12",
        "Layout" => "table-bordered table-hover",
        "HeadingRows" => 1,
        "HeadingColumns" => 0,
        "AllowHtml" => false,
    );

    /**
     * @var string
     */
    protected $type = "TableBlock";

    //====================================================================//
    // *******************************************************************//
    //  Block Getter & Setter Functions
    // *******************************************************************//
    //====================================================================//

    /**
     * Add a Row to Table
     *
     * @param array $row
     *
     * @return $this
     */
    public function addRow(array $row) : self
    {
        $this->data["rows"][] = $row;

        return $this;
    }

    /**
     * Add Multiple Rows to Table
     *
     * @param array $rows
     *
     * @return $this
     */
    public function addRows(array $rows) : self
    {
        foreach ($rows as $row) {
            if (!is_array($row)) {
                continue;
            }
            $this->addRow($row);
        }

        return $this;
    }

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
        //  Safety Check - Verify Rows
        if (isset($contents["rows"]) && $this->validateRows($contents["rows"])) {
            //==============================================================================
            //  Import Rows
            $this->setData(array("rows" => $contents["rows"]));
        }

        return $this;
    }

    /**
     * Verify Rows are Valid
     *
     * @param iterable $rows
     *
     * @return bool
     */
    private function validateRows(iterable $rows) : bool
    {
        //==============================================================================
        //  Verify Rows are inside an Array
        foreach ($rows as $row) {
            //==============================================================================
            //  Verify Cells are inside an Array
            if (!is_array($row) && !($row instanceof ArrayObject)) {
                return false;
            }
            foreach ($row as $cell) {
                //==============================================================================
                //  Verify Cell is scalar
                if (!is_scalar($cell)) {
                    return false;
                }
            }
        }

        return true;
    }
}
