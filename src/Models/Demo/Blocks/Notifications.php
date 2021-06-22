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

namespace Splash\Widgets\Models\Demo\Blocks;

use Splash\Widgets\Entity\Widget;
use Splash\Widgets\Services\FactoryService;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Demo Notifictaions Block definition
 */
class Notifications
{
    const TYPE = "Notifications";
    const ICON = "fa fa-fw fa-exclamation-triangle text-warning";
    const TITLE = "Notifications Block";
    const DESCRIPTION = "Demonstration Notifications Widget";

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
            ->addBlock("TextBlock", array( "AllowHtml" => true ))
            ->setText("<p>This is demo Notification Block. You can use it to render <b>any kind of alerts</b>.</p>")
            ->end()

            //==============================================================================
            // Create Notifications Block
            ->addBlock("NotificationsBlock")
            ->setError("My Widget Error")
            ->setWarning("My Widget Warning")
            ->setInfo("My Widget Info")
            ->setSuccess("My Widget Success")
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
