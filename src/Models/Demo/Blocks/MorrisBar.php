<?php

namespace Splash\Widgets\Models\Demo\Blocks;

use Splash\Widgets\Entity\Widget;
use Splash\Widgets\Services\FactoryService;

use Symfony\Component\Form\FormBuilderInterface;

/*
 * Demo Morris Bar Chart Block definition
 */
class MorrisBar
{
    const TYPE          =   "MorrisBar";
    const ICON          =   "fa fa-fw fa-bar-chart";
    const TITLE         =   "Morris Bar Chart Block";
    const DESCRIPTION   =   "Demonstration Morris Bar Chart";
    
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
                ->addBlock("MorrisBarBlock", self::blockOptions() )
                    ->setTitle("Morris Bar Chart")
                    ->setDataSet($Values)
                    ->setYKeys(["value","value2"])
                    ->setLabels(["Serie 1", "Serie 2"])
                    ->setChartOptions(array(
                        "barColors" => ["DeepPink","RoyalBlue","green"]
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
    
    public static function getOptions()
    {
        
        $Options = Widget::getDefaultOptions();
        
        $Options["UseCache"]    = False;
        
        return $Options;
        
    } 
    
}