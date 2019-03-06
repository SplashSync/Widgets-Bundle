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

namespace Splash\Widgets\Form;

use Splash\Widgets\Entity\Widget;
use Symfony\Component\Form\AbstractType;
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
        $optionsTab = $builder->create('rendering', \Mopa\Bundle\BootstrapBundle\Form\Type\TabType::class, array(
            'label' => 'options.label',
            'translation_domain' => "SplashWidgetsBundle",
            'icon' => ' fa fa-desktop',
            'inherit_data' => true,
            'attr' => array(
                'class' => 'well-sm',
            ),
        ));

        $this->addWidthColorForm($optionsTab);
        $this->addHeaderFooterForm($optionsTab);
        $this->addUseCacheForm($optionsTab);

        $builder->add($optionsTab);
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
            'property_path' => 'options[Width]',
            'label' => "options.width.label",
            'help_block' => "options.width.tooltip",
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
            //                'choices_as_values'         => True,
            'placeholder' => false,
            'widget_type' => 'inline',
            'expanded' => false,
        ));

        //====================================================================//
        // Widget Option - Box Bootstrap Color
        //====================================================================//

        $optionsTab->add("Color", ChoiceType::class, array(
            'required' => true,
            'property_path' => 'options[Color]',
            'label' => "options.color.label",
            'help_block' => "options.color.tooltip",
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
            //                'choices_as_values'         => True,
            'placeholder' => false,
            'widget_type' => 'inline',
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

        $optionsTab->add("Header", ChoiceType::class, array(
            'property_path' => 'options[Header]',
            'label' => "options.header.label",
            'help_block' => "options.header.tooltip",
            'translation_domain' => "SplashWidgetsBundle",
            'choice_translation_domain' => "SplashWidgetsBundle",
            'empty_data' => "1",
            'expanded' => true,
            'widget_type' => 'inline',
            //                'choices_as_values'         => True,
            'choices' => array(
                "actions.no" => '0',
                "actions.yes" => '1',
            ),
        ));

        //====================================================================//
        // Widget Option - Disable Footer Display
        //====================================================================//

        $optionsTab->add("Footer", ChoiceType::class, array(
            'property_path' => 'options[Footer]',
            'label' => "options.footer.label",
            'help_block' => "options.footer.tooltip",
            'translation_domain' => "SplashWidgetsBundle",
            'choice_translation_domain' => "SplashWidgetsBundle",
            'empty_data' => "1",
            'expanded' => true,
            'widget_type' => 'inline',
            //                'choices_as_values'         => True,
            'choices' => array(
                "actions.no" => '0',
                "actions.yes" => '1',
            ),
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

        $optionsTab->add("UseCache", ChoiceType::class, array(
            'property_path' => 'options[UseCache]',
            'label' => "options.usecache.label",
            'help_block' => "options.usecache.tooltip",
            'translation_domain' => "SplashWidgetsBundle",
            'choice_translation_domain' => "SplashWidgetsBundle",
            'empty_data' => "1",
            'expanded' => true,
            'widget_type' => 'inline',
            //                'choices_as_values'         => True,
            'choices' => array(
                "actions.no" => '0',
                "actions.yes" => '1',
            ),
        ));
    }
}
