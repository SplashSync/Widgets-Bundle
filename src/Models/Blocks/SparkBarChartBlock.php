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

namespace Splash\Widgets\Models\Blocks;

use ArrayObject;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Widget Spark Bar Chart Block
 * Simple Bar Chart
 */
class SparkBarChartBlock extends BaseBlock
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
        "values" => array(),
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
    protected $type = "SparkBarChartBlock";

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
        //  Import Values
        if (!empty($contents["values"])) {
            $this->setValues($contents["values"]);
        }

        return $this;
    }

    /**
     * Set Block Configuration from Parameters
     *
     * @param array $parameters
     *
     * @return $this
     */
    public function setParameters(array $parameters) : self
    {
        //==============================================================================
        //  Import Chart Height
        if (isset($parameters["sparkbar_height"])) {
            $this->setChartHeight($parameters["sparkbar_height"]);
        }
        //==============================================================================
        //  Import Bar Width
        if (isset($parameters["sparkbar_barwidth"])) {
            $this->setBarWidth($parameters["sparkbar_barwidth"]);
        }
        //==============================================================================
        //  Import Bar Color
        if (isset($parameters["sparkbar_barcolor"])) {
            $this->setBarColor($parameters["sparkbar_barcolor"]);
        }

        return $this;
    }

    //====================================================================//
    //  Block Form Builders for Customization
    //====================================================================//

    /**
     * Add SparkBar Height Parameter to Widget Form
     *
     * @param FormBuilderInterface $builder
     */
    public static function addHeightFormRow(FormBuilderInterface $builder) : void
    {
        $builder->add('SparkLineHeight', IntegerType::class, array(
            'label' => "blocks.sparkbar.height.tooltip",
            'translation_domain' => "SplashWidgetsBundle",
            'property_path' => 'parameters[sparkbar_height]',
            'required' => false,
        ));
    }

    /**
     * Add SparkBar Bar Width Parameter to Widget Form
     *
     * @param FormBuilderInterface $builder
     */
    public static function addBarWidthFormRow(FormBuilderInterface $builder) : void
    {
        $builder->add('SparkLineBarWidth', IntegerType::class, array(
            'label' => "blocks.sparkbar.barwidth.tooltip",
            'translation_domain' => "SplashWidgetsBundle",
            'property_path' => 'parameters[sparkbar_barwidth]',
            'required' => false,
        ));
    }

    /**
     * Add SparkBar Bar Color Parameter to Widget Form
     *
     * @param FormBuilderInterface $builder
     */
    public static function addBarColorFormRow(FormBuilderInterface $builder) : void
    {
        $builder->add('SparkLineBarColor', TextType::class, array(
            'label' => "blocks.sparkbar.barcolor.tooltip",
            'translation_domain' => "SplashWidgetsBundle",
            'property_path' => 'parameters[sparkbar_barcolor]',
            'required' => false,
        ));
    }

    //====================================================================//
    //  Block Getter & Setter Functions
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
     * Set Values
     *
     * @param array $values
     *
     * @return $this
     */
    public function setValues(array $values) : self
    {
        $this->data["values"] = $values;

        return $this;
    }

    /**
     * Get Values
     *
     * @return array
     */
    public function getValues() : array
    {
        return $this->data["values"];
    }

    /**
     * Set Chart Height
     *
     * @param int $value
     *
     * @return $this
     */
    public function setChartHeight(int $value) : self
    {
        $this->options["ChartOptions"]["height"] = $value;

        return $this;
    }

    /**
     * Set Chart Width
     *
     * @param int $value
     *
     * @return $this
     */
    public function setChartWidth(int $value) : self
    {
        $this->options["ChartOptions"]["width"] = $value;

        return $this;
    }

    /**
     * Set Bar Width
     *
     * @param int $value
     *
     * @return $this
     */
    public function setBarWidth(int $value) : self
    {
        $this->options["ChartOptions"]["barwidth"] = $value;

        return $this;
    }

    /**
     * Set Bar Color
     *
     * @param string $value
     *
     * @return $this
     */
    public function setBarColor(string $value) : self
    {
        $this->options["ChartOptions"]["bar-color"] = $value;

        return $this;
    }
}
