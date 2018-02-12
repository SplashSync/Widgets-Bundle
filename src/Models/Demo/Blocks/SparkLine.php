<?php

namespace Splash\Widgets\Models\Demo\Blocks;

use Splash\Widgets\Entity\Widget;
use Splash\Widgets\Services\FactoryService;

use Symfony\Component\Form\FormBuilderInterface;

/*
 * Demo SparkLine Line Chart Block definition
 */
class SparkLine
{
    const TYPE          =   "SparkLine";
    const ICON          =   "fa fa-fw fa-area-chart";
    const TITLE         =   "Sparline Line Chart Block";
    const DESCRIPTION   =   "Demonstration Sparline Line Chart";
    
    public static function build(FactoryService $Factory, array $Parameters)
    {
        $Values = array(); 
        for ($i=0 ; $i<24 ; $i++) {
            $Values[] = rand(0,100); 
        }

        //==============================================================================
        // Create Sparkline Line Chart Block 
        $BarGraph = $Factory->addBlock("SparkLineChartBlock", self::blockOptions() );
        
        $BarGraph
                ->setTitle("Sparkline Line Chart")
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
//                "fill-color"    => "Silver" 
            ),
            
        );  
    }        
    
}