<?php

namespace Splash\Widgets\Models\Demo\Blocks;

use Splash\Widgets\Entity\Widget;
use Splash\Widgets\Services\FactoryService;

use Symfony\Component\Form\FormBuilderInterface;

/*
 * Demo Morris Area Chart Block definition
 */
class MorrisArea
{
    const TYPE          =   "MorrisArea";
    const ICON          =   "fa fa-fw fa-area-chart";
    const TITLE         =   "Morris Area Chart Block";
    const DESCRIPTION   =   "Demonstration Morris Area Chart";
    
    public static function build(FactoryService $Factory, array $Parameters)
    {
        $Next = rand(0,100);
        $Next2 = rand(0,100);
        $Values = array(); 
        for ($i=1 ; $i<25 ; $i++) {
            $Values[] = array( 
                "label"     => "2017 W" . $i, 
                "value"     => $Next,
                "value2"    => $Next2
                ); 
            $Next += rand(-50,50);
            $Next2 += rand(-50,50);
        }

        $Factory
                
        //==============================================================================
        // Create Morris Line Chart Block 
                ->addBlock("MorrisAreaBlock", self::blockOptions() )
                    ->setTitle("Morris Area Chart")
                    ->setDataSet($Values)
                    ->setYKeys(["value","value2"])
                    ->setLabels(["Serie 1", "Serie 2"])
                    ->setChartOptions(array(
                        "lineColors" => ["DeepPink","RoyalBlue","green"]
                    ))
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
            "AllowHtml"             => False,
            "ChartOptions"          => array(
//                "fill-color"    => "Silver" 
            ),
            
        );  
    }        
    
}