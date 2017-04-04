<?php

namespace Splash\Widgets\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use Splash\Widgets\Entity\Widget;

class WidgetOptionsType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $OptionsTab = $builder->create('rendering', \Mopa\Bundle\BootstrapBundle\Form\Type\TabType::class, array(
            'label'                 => 'options.label',
            'translation_domain'    => "SplashWidgetsBundle",
            'icon'                  => ' fa fa-desktop',
            'inherit_data'          => true,
        ));
        
        
        //====================================================================//
        // Widget Option - Box Bootstrap Width  
        //====================================================================//

        $OptionsTab->add("Width", ChoiceType::class, array(
                'required'                  => True,
                'property_path'             => 'options[Width]',
                'label'                     => "options.width.label",
                'help_block'                => "options.width.tooltip",
                'choices'                   => array(
                    "options.width.xs"          => Widget::$WIDTH_XS, 
                    "options.width.sm"          => Widget::$WIDTH_SM, 
                    "options.width.m"           => Widget::$WIDTH_M, 
                    "options.width.l"           => Widget::$WIDTH_L, 
                    "options.width.xl"          => Widget::$WIDTH_XL, 
                    ),
                'empty_data'                => Widget::$WIDTH_M,
                'translation_domain'        => "SplashWidgetsBundle",
                'choice_translation_domain' => "SplashWidgetsBundle",            
                'choices_as_values'         => True,            
                'placeholder'               => False,
                'widget_type'               => 'inline',
                'expanded'                  => false,
            ));
        
        //====================================================================//
        // Widget Option - Box Bootstrap Width  
        //====================================================================//

        $OptionsTab->add("Color", ChoiceType::class, array(
                'required'                  => True,
                'property_path'             => 'options[Color]',
                'label'                     => "options.color.label",
                'help_block'                => "options.color.tooltip",
                'choices'                   => array(
                    "options.color.default"     => Widget::$COLOR_DEFAULT, 
                    "options.color.primary"     => Widget::$COLOR_PRIMARY, 
                    "options.color.success"     => Widget::$COLOR_SUCCESS, 
                    "options.color.info"        => Widget::$COLOR_INFO, 
                    "options.color.warning"     => Widget::$COLOR_WARNING, 
                    "options.color.danger"      => Widget::$COLOR_DANGER, 
                    ),
                'empty_data'                => Widget::$COLOR_DEFAULT,
                'translation_domain'        => "SplashWidgetsBundle",
                'choice_translation_domain' => "SplashWidgetsBundle",            
                'choices_as_values'         => True,            
                'placeholder'               => False,
                'widget_type'               => 'inline',
                'expanded'                  => false,
            ));
        
        //====================================================================//
        // Widget Option - Diasble Header Display 
        //====================================================================//

        //==============================================================================
        // Simple CheckBox 
        $OptionsTab->add("Header", ChoiceType::class, array(
                'property_path'             => 'options[Header]',            
                'label'                     => "options.header.label",
                'help_block'                => "options.header.tooltip",
                'translation_domain'        => "SplashWidgetsBundle",
                'choice_translation_domain' => "SplashWidgetsBundle",            
                'empty_data'                => "1",
                'expanded'                  => true,
                'widget_type'               => 'inline',
                'choices'       => array(
                        "actions.no"    => '0', 
                        "actions.yes"   => '1', 
                    ),
            ));          
            
        //====================================================================//
        // Widget Option - Diasble Footer Display 
        //====================================================================//

        $OptionsTab->add("Footer", ChoiceType::class, array(
                'property_path'             => 'options[Footer]',            
                'label'                     => "options.footer.label",
                'help_block'                => "options.footer.tooltip",
                'translation_domain'        => "SplashWidgetsBundle",
                'choice_translation_domain' => "SplashWidgetsBundle",            
                'empty_data'                => "1",
                'expanded'                  => true,
                'widget_type'               => 'inline',
                'choices'       => array(
                        "actions.no"    => '0', 
                        "actions.yes"   => '1', 
                    ),
                ));      
        
        $builder->add($OptionsTab);
        
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'splash_widgets_rendering_forms';
    }
}