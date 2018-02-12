<?php

namespace Splash\Widgets\Tests\Blocks;

use Symfony\Component\Form\FormBuilderInterface;

use Splash\Widgets\Entity\Widget;
use Splash\Widgets\Services\FactoryService;

/*
 * Demo Test Block definition
 */
class Test
{
    const TYPE          =   "Test";
    const ICON          =   "fa fa-fw fa-github";
    const TITLE         =   "Test Block";
    const DESCRIPTION   =   "Demonstration Text Widget";
    
    public static function build(FactoryService $Factory, array $Parameters)
    {
        $Factory
                
        //==============================================================================
        // Create Text Block 
            ->addBlock("TextBlock", self::blockOptions() )
                ->setText("<p>This is the Test Block. You can use it test Passing Parameters to Blocks.</p>")
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