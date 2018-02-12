<?php

namespace Splash\Widgets\Models\Demo\Blocks;

use Symfony\Component\Form\FormBuilderInterface;

use Splash\Widgets\Entity\Widget;
use Splash\Widgets\Models\WidgetBlock    as Block;

use Symfony\Component\EventDispatcher\GenericEvent;

use Splash\Widgets\Services\FactoryService;

use Splash\Widgets\Models\Interfaces\WidgetProviderInterface;

use Splash\Widgets\Models\Blocks\SparkBarChartBlock;

/*
 * Demo SparkLine Bar Chart Block definition
 */
class SparkBar
{
    const TYPE          =   "SparkBar";
    const ICON          =   "fa fa-bar-chart";
    const TITLE         =   "Sparline Bar Chart Block";
    const DESCRIPTION   =   "Demonstration Sparline Bar Chart";
    
    public static function build(FactoryService $Factory, array $Parameters)
    {
        $Values = array(); 
        for ($i=0 ; $i<24 ; $i++) {
            $Values[] = rand(0,100); 
        }

        //==============================================================================
        // Create Sparkline Line Chart Block 
        $BarGraph = $Factory->addBlock("SparkBarChartBlock", self::blockOptions() );
        
        $BarGraph
                ->setTitle("Sparkline Bar Chart")
                ->setValues($Values)
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
            "AllowHtml"             => False,
            "ChartOptions"          => array(
                "bar-color"     => "DeepSkyBlue", 
                "barwidth"      => "10" 
            ),
            
        );  
    }        
    
}