<?php

namespace Splash\Widgets\Models\Demo\Blocks;

use Symfony\Component\Form\FormBuilderInterface;

use Splash\Widgets\Entity\Widget;
use Splash\Widgets\Services\FactoryService;

/*
 * Demo Table Block definition
 */
class Table
{
    const TYPE          =   "Table";
    const ICON          =   "fa fa-fw fa-table";
    const TITLE         =   "Table Block";
    const DESCRIPTION   =   "Demonstration Table Widget";
    
    public static function build(FactoryService $Factory, array $Parameters)
    {
        $Factory
                
            //==============================================================================
            // Create Text Block 
            ->addBlock("TextBlock", self::blockOptions() )
                ->setText("<p>This is demo Table Block. You can use it to render... <b>data tables</b>.</p>")
            ->end()
        
            //==============================================================================
            // Create Table Block 
            ->addBlock("TableBlock" , self::blockOptions() )
                ->addRow(["One", "Two", "Treeee!"])
                ->addRow(["One", "<b>Two</b>", "Treeee!"])
                ->addRow(["One", "Two", "Treeee!"])
                ->addRow(["One", "Two", "Treeee!"])
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