<?php

namespace Splash\Widgets\Models\Demo\Blocks;

use Symfony\Component\Form\FormBuilderInterface;

use Splash\Widgets\Entity\Widget;
use Splash\Widgets\Services\FactoryService;

/*
 * Demo Notifictaions Block definition
 */
class Notifications
{
    const TYPE          =   "Notifications";
    const ICON          =   "fa fa-fw fa-exclamation-triangle text-warning";
    const TITLE         =   "Notifications Block";
    const DESCRIPTION   =   "Demonstration Notifications Widget";
    
    public static function build(FactoryService $Factory, array $Parameters)
    {
        $Factory
                
            //==============================================================================
            // Create Text Block 
            ->addBlock("TextBlock", array( "AllowHtml" => True ) )
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