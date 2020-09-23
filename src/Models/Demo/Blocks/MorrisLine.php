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

namespace Splash\Widgets\Models\Demo\Blocks;

use Splash\Widgets\Entity\Widget;
use Splash\Widgets\Services\FactoryService;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Demo Morris Line Chart Block definition
 */
class MorrisLine
{
    const TYPE = "MorrisLine";
    const ICON = "fa fa-fw fa-area-chart fas fa-chart-area";
    const TITLE = "Morris Line Chart Block";
    const DESCRIPTION = "Demonstration Morris Line Chart";

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
        $next = rand(0, 100);
        $next2 = rand(0, 100);
        $values = array();
        for ($i = 1; $i < 25; $i++) {
            $values[] = array(
                "label" => "2017 W".$i,
                "value" => $next,
                "value2" => $next2,
            );
            $next += rand(-50, 50);
            $next2 += rand(-50, 50);
        }

        $factory

        //==============================================================================
        // Create Morris Line Chart Block
            ->addBlock("MorrisLineBlock", self::blockOptions())
            ->setTitle("Morris Line Chart")
            ->setDataSet($values)
            ->setYKeys(array("value", "value2"))
            ->setLabels(array("Serie 1", "Serie 2"))
            ->setChartOptions(array(
                "lineColors" => array("BlueViolet", "blue", "green"),
            ))
            ->end()

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
                //                "fill-color"    => "Silver"
            ),
        );
    }
}
