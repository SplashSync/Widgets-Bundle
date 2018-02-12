<?php

namespace Splash\Widgets\Models\Demo\Blocks;

use Symfony\Component\Form\FormBuilderInterface;

use Splash\Widgets\Entity\Widget;
use Splash\Widgets\Services\FactoryService;

/*
 * Demo SparkInfos Block definition
 */
class SparkInfos
{
    const TYPE          =   "SparkInfos";
    const ICON          =   "fa fa-fw fa-info-circle text-info";
    const TITLE         =   "Informations Block";
    const DESCRIPTION   =   "Demonstration Spark Infos Widget";
    
    public static function build(FactoryService $Factory, array $Parameters)
    {
        $Factory
                
            //==============================================================================
            // Create SparkInfo Block 
            ->addBlock("SparkInfoBlock", self::blockOptions() )
                ->setTitle("Fontawesome Icon")
                ->setFaIcon("magic")
                ->setValue("100%")
                ->setSeparator(True)
            ->end()
                
            //==============================================================================
            // Create SparkInfo Block 
            ->addBlock("SparkInfoBlock", self::blockOptions() )
                ->setTitle("Glyph Icon")
                ->setGlyphIcon("asterisk")
                ->setValue("100%")
                ->setSeparator(True)
            ->end()
                
            //==============================================================================
            // Create SparkInfo Block 
            ->addBlock("SparkInfoBlock", self::blockOptions() )
                ->setTitle("Sparkline Chart")
                ->setChart(array("0:30", "10:20", "20:20", "30:20", "-10:10", "15:25", "30:40", "80:90", 90, 100, 90, 80))
                ->setSeparator(True)
            ->end()
                
            //==============================================================================
            // Create SparkInfo Block 
            ->addBlock("SparkInfoBlock", self::blockOptions() )
                ->setTitle("Sparkline Pie")
                ->setPie(array("10", "20", "30"))
                ->setSeparator(True)
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
            "Width"                 => Widget::$WIDTH_XS,
            "AllowHtml"             => True,
            
        );  
    }        
    
}