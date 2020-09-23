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
use Splash\Widgets\Models\Traits\ParametersTrait;
use Splash\Widgets\Services\FactoryService;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Demo Text Block definition
 */
class Dates
{
    use ParametersTrait;

    const TYPE = "Dates";
    const ICON = "fa fa-fw far fa-clock";
    const TITLE = "Dates Block";
    const DESCRIPTION = "Demonstration of Dates Widget";

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
        if (isset($parameters["DatePreset"]) && self::isPreset($parameters["DatePreset"])) {
            $parameters = self::getDatesArray($parameters["DatePreset"]);
        }
        $start = isset($parameters['DateStart'])    ? $parameters['DateStart']->format('Y-m-d H:i:s') : "Undefined";
        $end = isset($parameters['DateEnd'])      ? $parameters['DateEnd']->format('Y-m-d H:i:s') : "Undefined";

        $factory

            //==============================================================================
            // Create Text Block
            ->addBlock("TextBlock", self::blockTextOptions())
            ->setText("<p class='text-center'>This is demo for Dates Selections. It shows Start & End Dates for widgets rendering.</p>")
            ->end()

            //==============================================================================
            // Create SparkInfo Block
            ->addBlock("SparkInfoBlock", self::blockOptions())
            ->setTitle("Start Date")
            ->setFaIcon("play")
            ->setValue($start)
            ->end()

            //==============================================================================
            // Create SparkInfo Block
            ->addBlock("SparkInfoBlock", self::blockOptions())
            ->setTitle("End Date")
            ->setFaIcon("stop")
            ->setValue($end)
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
     * Get Text Block Options
     *
     * @return array
     */
    public static function blockTextOptions() : array
    {
        //==============================================================================
        // Create Block Options
        return array(
            "Width" => Widget::$widthXl,
            "AllowHtml" => true,
        );
    }

    /**
     * Get Others Block Options
     *
     * @return array
     */
    public static function blockOptions() : array
    {
        //==============================================================================
        // Create Block Options
        return array(
            "Width" => Widget::$widthDefault,
            "AllowHtml" => false,
        );
    }
}
