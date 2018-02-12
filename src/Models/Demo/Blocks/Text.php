<?php

namespace Splash\Widgets\Models\Demo\Blocks;

use Symfony\Component\Form\FormBuilderInterface;

use Splash\Widgets\Entity\Widget;
use Splash\Widgets\Services\FactoryService;

/*
 * Demo Text Block definition
 */
class Text
{
    const TYPE          =   "Text";
    const ICON          =   "fa fa-fw fa-font";
    const TITLE         =   "Text Block";
    const DESCRIPTION   =   "Demonstration Text Widget";
    
    public static function build(FactoryService $Factory, array $Parameters)
    {
        $Factory
                
        //==============================================================================
        // Create Text Block 
            ->addBlock("TextBlock", self::blockOptions() )
                ->setText("<p>This is demo Simple Text Block. You can use it to render <b>Raw HTML Contents</b>.</p>")
            ->end()
                
            ;
    }

    
    public static function populateWidgetForm(FormBuilderInterface $builder)
    {
        return;
    }    

    
    public static function blockOptions()
    {
        //==============================================================================
        // Create Block Options 
        return array(
            "Width"                 => Widget::$WIDTH_XL,
            "AllowHtml"             => True,
            
        );  
    }        
    
}