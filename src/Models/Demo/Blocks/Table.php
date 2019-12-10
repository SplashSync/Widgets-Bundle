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
 * Demo Table Block definition
 */
class Table
{
    const TYPE = "Table";
    const ICON = "fa fa-fw fa-table";
    const TITLE = "Table Block";
    const DESCRIPTION = "Demonstration Table Widget";

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
            // Create Text Block
            ->addBlock("TextBlock", self::blockOptions())
            ->setText("<p>This is demo Table Block. You can use it to render... <b>data tables</b>.</p>")
            ->end()

            //==============================================================================
            // Create Table Block
            ->addBlock("TableBlock", self::blockOptions())
            ->addRow(array("One", "Two", "Treeee!"))
            ->addRow(array("One", "<b>Two</b>", "Treeee!"))
            ->addRow(array("One", "Two", "Treeee!"))
            ->addRow(array("One", "Two", "Treeee!"))
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
            "AllowHtml" => true,
        );
    }
}
