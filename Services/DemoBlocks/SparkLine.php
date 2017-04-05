<?php

namespace Splash\Widgets\Services\DemoBlocks;

use Symfony\Component\Form\FormBuilderInterface;

use Splash\Widgets\Entity\Widget;
use Splash\Widgets\Models\WidgetBlock    as Block;

use Symfony\Component\EventDispatcher\GenericEvent;

use Splash\Widgets\Services\FactoryService;

use Splash\Widgets\Models\Interfaces\WidgetProviderInterface;

use Splash\Widgets\Models\Blocks\SparkBarChartBlock;

/*
 * Demo SparkLine Chart Block definition
 */
class SparkLine
{
    const TYPE          =   "Splash.Widgets.Samples";
    const TITLE         =   "Sparline Line Chart Block";
    const DESCRIPTION   =   "Demonstration Sparline Line Chart";
    
    public static function build(FactoryService $Factory, array $Parameters)
    {
        //==============================================================================
        // Create Block Options 
        $BlockOptions = array(
            "Width"          => Widget::$WIDTH_XL,
        );  
        
        //==============================================================================
        // Create Sparkline Line Chart Block 
        $BarGraph = $Factory->addBlock("SparkBarChartBlock",$BlockOptions);
        
        $BarGraph
                ->setTitle("Sparkline Line Chart")
                ->setValues(array("1", "3", "5", "7", "12", "24", "32", "64", "36", "24", "-5", "30", "10", "20", "30", "10", "20", "30"));
        
        if ( isset($Parameters["sparkbar_height"]) ) {
            $BarGraph->setChartHeight($Parameters["sparkbar_height"]);
        }
        
        if ( isset($Parameters["sparkbar_barwidth"]) ) {
            $BarGraph->setBarWidth($Parameters["sparkbar_barwidth"]);
        }
        if ( isset($Parameters["sparkbar_barcolor"]) ) {
            $BarGraph->setBarColor($Parameters["sparkbar_barcolor"]);
        }
    }

    
}