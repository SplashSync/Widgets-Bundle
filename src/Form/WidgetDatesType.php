<?php

namespace Splash\Widgets\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use Splash\Widgets\Entity\Widget;

class WidgetDatesType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $DatesTab = $builder->create('dates', \Mopa\Bundle\BootstrapBundle\Form\Type\TabType::class, array(
            'label'                 => 'dates.label',
            'translation_domain'    => "SplashWidgetsBundle",
            'icon'                  => ' fa fa-clock-o',
            'inherit_data'          => true,
            'attr'              => array (
                'class' => 'well-sm',
            )
        ));

        //====================================================================//
        // Widget Option - Select Dates 
        //====================================================================//

        $DatesTab->add("Dates", ChoiceType::class, array(
                'required'                  => True,
                'property_path'             => 'parameters[DatePreset]',
                'label'                     => "dates.label",
                'help_block'                => "dates.tooltip",
                'choices'       => array(
                    "dates.D"       =>      "D", 
                    "dates.W"       =>      "W", 
                    "dates.M"       =>      "M", 
                    "dates.Y"       =>      "Y", 
                    "dates.LW"      =>      "LW", 
                    "dates.L2W"     =>      "L2W", 
                    "dates.LM"      =>      "LM", 
                    "dates.LY"      =>      "LY", 
                    "dates.PD"      =>      "PD", 
                    "dates.PW"      =>      "PW", 
                    "dates.PM"      =>      "PM", 
                    "dates.PY"      =>      "PY", 
                ),
                'empty_data'                => "options.dates.M",
                'translation_domain'        => "SplashWidgetsBundle",
                'choice_translation_domain' => "SplashWidgetsBundle",      
                'placeholder'               => False,
                'expanded'                  => false,
                'choices_as_values'         => True,            
            ));     
        
        $builder->add($DatesTab);
        
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'splash_widgets_render_widgeting_forms';
    }
}