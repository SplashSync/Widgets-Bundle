<?php

namespace Splash\Widgets\Models\Demo\Blocks;

use Symfony\Component\Form\FormBuilderInterface;

use Splash\Widgets\Entity\Widget;
use Splash\Widgets\Services\FactoryService;
use Splash\Widgets\Models\Traits\ParametersTrait;

/*
 * Demo Text Block definition
 */
class Dates
{
    use ParametersTrait;
    
    const TYPE          =   "Dates";
    const ICON          =   "fa fa-fw fa-clock-o";
    const TITLE         =   "Dates Block";
    const DESCRIPTION   =   "Demonstration of Dates Widget";
    
    public static function build(FactoryService $Factory, array $Parameters)
    {

        if ( isset($Parameters["DatePreset"]) && self::isPreset($Parameters["DatePreset"])) {
            $Parameters = self::getDatesArray($Parameters["DatePreset"]);
        } 
        $Start  =   isset($Parameters ['DateStart'])    ? $Parameters ['DateStart']->format('Y-m-d H:i:s') : "Undefined";
        $End    =   isset($Parameters ['DateEnd'])      ? $Parameters ['DateEnd']->format('Y-m-d H:i:s') : "Undefined";
        
        $Factory
                
            //==============================================================================
            // Create Text Block 
            ->addBlock("TextBlock", self::blockTextOptions() )
                ->setText("<p class='text-center'>This is demo for Dates Selections. It shows Start & End Dates for widgets rendering.</p>")
            ->end()
                
            //==============================================================================
            // Create SparkInfo Block 
            ->addBlock("SparkInfoBlock", self::blockOptions() )
                ->setTitle("Start Date")
                ->setFaIcon("play")
                ->setValue($Start)
            ->end()  
                
            //==============================================================================
            // Create SparkInfo Block 
            ->addBlock("SparkInfoBlock", self::blockOptions() )
                ->setTitle("End Date")
                ->setFaIcon("stop")
                ->setValue($End)
            ->end()                  
                
            ;
    }

    
    public static function populateWidgetForm(FormBuilderInterface $builder)
    {
        return;
    }    

    public static function blockTextOptions()
    {
        //==============================================================================
        // Create Block Options 
        return array(
            "Width"                 => Widget::$WIDTH_XL,
            "AllowHtml"             => True,
        );  
    }        
    
    public static function blockOptions()
    {
        //==============================================================================
        // Create Block Options 
        return array(
            "Width"                 => Widget::$WIDTH_DEFAULT,
            "AllowHtml"             => False,
            
        );  
    }        
    
}