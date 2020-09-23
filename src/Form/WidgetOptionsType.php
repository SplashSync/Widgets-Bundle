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

namespace Splash\Widgets\Form;

use Splash\Widgets\Entity\Widget;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Widget Rendering Options Form Type
 */
class WidgetOptionsType extends AbstractType
{
    /**
     * Build the Form
     *
     * @param FormBuilderInterface $builder
     * @param array                $options
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function buildForm(FormBuilderInterface $builder, array $options) : void
    {
        $this->addWidthColorForm($builder);
        $this->addHeaderFooterForm($builder);
        $this->addUseCacheForm($builder);
    }

    /**
     * @return string
     */
    public function getName() : string
    {
        return 'splash_widgets_render_widgeting_forms';
    }

    /**
     * Build Width & Color Select Form Widget
     *
     * @param FormBuilderInterface $optionsTab
     */
    private function addWidthColorForm(FormBuilderInterface &$optionsTab) : void
    {
        //====================================================================//
        // Widget Option - Box Bootstrap Width
        //====================================================================//

        $optionsTab->add("Width", ChoiceType::class, array(
            'required' => true,
            'property_path' => '[Width]',
            'label' => "options.width.tooltip",
            'choices' => array(
                "options.width.xs" => Widget::$widthXs,
                "options.width.sm" => Widget::$widthSm,
                "options.width.m" => Widget::$widthM,
                "options.width.l" => Widget::$widthL,
                "options.width.xl" => Widget::$widthXl,
            ),
            'empty_data' => Widget::$widthM,
            'translation_domain' => "SplashWidgetsBundle",
            'choice_translation_domain' => "SplashWidgetsBundle",
            'placeholder' => false,
            'expanded' => false,
        ));

        //====================================================================//
        // Widget Option - Box Bootstrap Color
        //====================================================================//

        $optionsTab->add("Color", ChoiceType::class, array(
            'required' => true,
            'property_path' => '[Color]',
            'label' => "options.color.tooltip",
            'choices' => array(
                "options.color.none" => Widget::$colorNone,
                "options.color.default" => Widget::$colorDefault,
                "options.color.primary" => Widget::$colorPrimary,
                "options.color.success" => Widget::$colorSuccess,
                "options.color.info" => Widget::$colorInfo,
                "options.color.warning" => Widget::$colorWarning,
                "options.color.danger" => Widget::$colorDanger,
            ),
            'empty_data' => Widget::$colorDefault,
            'translation_domain' => "SplashWidgetsBundle",
            'choice_translation_domain' => "SplashWidgetsBundle",
            'placeholder' => false,
            'expanded' => false,
        ));
    }

    /**
     * Build Header & Footer Select Form Widget
     *
     * @param FormBuilderInterface $optionsTab
     */
    private function addHeaderFooterForm(FormBuilderInterface &$optionsTab) : void
    {
        //====================================================================//
        // Widget Option - Disable Header Display
        //====================================================================//

        $optionsTab->add("Header", CheckboxType::class, array(
            'label' => "options.header.tooltip",
            'label_attr' => array("class" => "checkbox-custom"),
            'translation_domain' => "SplashWidgetsBundle",
            'required' => false,
            'data' => true,
        ));

        //====================================================================//
        // Widget Option - Disable Footer Display
        //====================================================================//

        $optionsTab->add("Footer", CheckboxType::class, array(
            'label' => "options.footer.tooltip",
            'label_attr' => array("class" => "checkbox-custom"),
            'translation_domain' => "SplashWidgetsBundle",
            'required' => false,
            'data' => true,
        ));
    }

    /**
     * Use Cache Widget
     *
     * @param FormBuilderInterface $optionsTab
     */
    private function addUseCacheForm(FormBuilderInterface &$optionsTab) : void
    {
        //====================================================================//
        // Widget Option - Caching Options
        //====================================================================//

        $optionsTab->add("UseCache", CheckboxType::class, array(
            'property_path' => '[UseCache]',
            'label' => "options.usecache.tooltip",
            'label_attr' => array("class" => "checkbox-custom"),
            'translation_domain' => "SplashWidgetsBundle",
            'required' => false,
            'data' => true,
        ));
    }
}
