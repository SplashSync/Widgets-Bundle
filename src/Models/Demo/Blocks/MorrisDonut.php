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
 * Demo Morris Donut Chart Block definition
 */
class MorrisDonut
{
    const TYPE = "MorrisDonut";
    const ICON = "fa fa-fw fa-pie-chart fas fa-chart-pie ";
    const TITLE = "Morris Donut Chart Block";
    const DESCRIPTION = "Demonstration Morris Donut Chart";

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
        for ($i = 1; $i < 5; $i++) {
            $values[] = array(
                "label" => "Block".$i,
                "value" => rand(10, 50),
            );
        }

        $factory

        //==============================================================================
        // Create Morris Line Chart Block
            ->addBlock("MorrisDonutBlock", self::blockOptions())
            ->setTitle("Morris Donut Chart")
            ->setDataSet($values)
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
                "colors" => array("BlueViolet", "blue", "green", "pink"),
                //                "fill-color"    => "Silver"
            ),
        );
    }
}
