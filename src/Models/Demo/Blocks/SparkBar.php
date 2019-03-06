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

namespace Splash\Widgets\Models\Demo\Blocks;

use Splash\Widgets\Entity\Widget;
use Splash\Widgets\Services\FactoryService;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Demo SparkLine Bar Chart Block definition
 */
class SparkBar
{
    const TYPE = "SparkBar";
    const ICON = "fa fa-bar-chart";
    const TITLE = "Sparline Bar Chart Block";
    const DESCRIPTION = "Demonstration Sparline Bar Chart";

    /**
     * Build Block
     *
     * @param FactoryService $factory
     * @param array          $parameters
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public static function build(FactoryService $factory, array $parameters) : void
    {
        $values = array();
        for ($i = 0; $i < 24; $i++) {
            $values[] = rand(0, 100);
        }

        //==============================================================================
        // Create Sparkline Line Chart Block
        $barGraph = $factory->addBlock("SparkBarChartBlock", self::blockOptions());

        $barGraph
            ->setTitle("Sparkline Bar Chart")
            ->setValues($values)
            ;
    }

    /**
     * Populate Block on Widget Form
     *
     * @param FormBuilderInterface $builder
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public static function populateWidgetForm(FormBuilderInterface $builder) : void
    {
    }

    /**
     * Get Block Options
     *
     * @return array
     */
    public static function blockOptions() : array
    {
        //==============================================================================
        // Create Block Options
        return array(
            "Width" => Widget::$widthXl,
            "AllowHtml" => false,
            "ChartOptions" => array(
                "bar-color" => "DeepSkyBlue",
                "barwidth" => "10",
            ),
        );
    }
}
