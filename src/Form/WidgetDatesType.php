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

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Widget Dates Range Selector Form Type
 */
class WidgetDatesType extends AbstractType
{
    /**
     * Build Form Widget
     *
     * @param FormBuilderInterface $builder
     * @param array                $options
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function buildForm(FormBuilderInterface $builder, array $options) : void
    {
        //====================================================================//
        // Widget Option - Select Dates
        //====================================================================//

        $builder->add("DatePreset", ChoiceType::class, array(
            'required' => true,
            'property_path' => '[DatePreset]',
            'label' => "dates.tooltip",
            'choices' => array(
                "dates.D" => "D",
                "dates.W" => "W",
                "dates.M" => "M",
                "dates.Y" => "Y",
                "dates.LW" => "LW",
                "dates.L2W" => "L2W",
                "dates.LM" => "LM",
                "dates.LY" => "LY",
                "dates.PD" => "PD",
                "dates.PW" => "PW",
                "dates.PM" => "PM",
                "dates.PY" => "PY",
            ),
            'empty_data' => "options.dates.M",
            'translation_domain' => "SplashWidgetsBundle",
            'choice_translation_domain' => "SplashWidgetsBundle",
            'placeholder' => false,
            'expanded' => false,
            //                'choices_as_values'         => True,
        ));
    }

    /**
     * @return string
     */
    public function getName() : string
    {
        return 'splash_widgets_render_widgeting_forms';
    }
}
