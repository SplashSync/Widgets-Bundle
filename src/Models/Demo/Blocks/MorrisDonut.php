<?php

namespace Splash\Widgets\Models\Demo\Blocks;

use Splash\Widgets\Entity\Widget;
use Splash\Widgets\Services\FactoryService;

use Symfony\Component\Form\FormBuilderInterface;

/*
 * Demo Morris Donut Chart Block definition
 */
class MorrisDonut
{
    const TYPE          =   "MorrisDonut";
    const ICON          =   "fa fa-fw fa-pie-chart ";
    const TITLE         =   "Morris Donut Chart Block";
    const DESCRIPTION   =   "Demonstration Morris Donut Chart";
    
    public static function build(FactoryService $Factory, array $Parameters)
    {
        $Values = array(); 
        for ($i=1 ; $i<5 ; $i++) {
            $Values[] = array( 
                "label"     => "Block" . $i, 
                "value"     => rand(10,50),
                ); 
        }

        $Factory
                
        //==============================================================================
        // Create Morris Line Chart Block 
                ->addBlock("MorrisDonutBlock", self::blockOptions() )
                    ->setTitle("Morris Donut Chart")
                    ->setDataSet($Values)
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
                "colors" => ["BlueViolet","blue","green","pink"],
//                "fill-color"    => "Silver" 
            ),
            
        );  
    }        
    
}