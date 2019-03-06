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
 * Demo SparkInfos Block definition
 */
class SparkInfos
{
    const TYPE = "SparkInfos";
    const ICON = "fa fa-fw fa-info-circle text-info";
    const TITLE = "Informations Block";
    const DESCRIPTION = "Demonstration Spark Infos Widget";

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
        $factory

            //==============================================================================
            // Create SparkInfo Block
            ->addBlock("SparkInfoBlock", self::blockOptions())
            ->setTitle("Fontawesome Icon")
            ->setFaIcon("magic")
            ->setValue("100%")
            ->setSeparator(true)
            ->end()

            //==============================================================================
            // Create SparkInfo Block
            ->addBlock("SparkInfoBlock", self::blockOptions())
            ->setTitle("Glyph Icon")
            ->setGlyphIcon("asterisk")
            ->setValue("100%")
            ->setSeparator(true)
            ->end()

            //==============================================================================
            // Create SparkInfo Block
            ->addBlock("SparkInfoBlock", self::blockOptions())
            ->setTitle("Sparkline Chart")
            ->setChart(array("0:30", "10:20", "20:20", "30:20", "-10:10", "15:25", "30:40", "80:90", 90, 100, 90, 80))
            ->setSeparator(true)
            ->end()

            //==============================================================================
            // Create SparkInfo Block
            ->addBlock("SparkInfoBlock", self::blockOptions())
            ->setTitle("Sparkline Pie")
            ->setPie(array("10", "20", "30"))
            ->setSeparator(true)
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
            "Width" => Widget::$widthXs,
            "AllowHtml" => true,
        );
    }
}
