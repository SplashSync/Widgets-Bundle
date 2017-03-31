<?php

namespace Splash\Widgets\Models\Interfaces;

/*
 * @abstract    Widget provider Interface
 */
interface WidgetProviderInterface 
{
    
    /**
     * @abstract   Read Widget Contents
     * 
     * @param      string   $WidgetId           Widgets Type Identifier 
     * 
     * @return     Widget 
     */    
    public function getWidget($WidgetId);

    /**
     * @abstract   Return Widget Options Array 
     * 
     * @param      string   $WidgetId           Widgets Type Identifier 
     * 
     * @return     array
     */    
    public function getWidgetOptions($WidgetId) : array;    

    /**
     * @abstract   Return Widget Parameters Array 
     * 
     * @param      string   $WidgetId           Widgets Type Identifier 
     * 
     * @return     array
     */    
    public function getWidgetParameters($WidgetId) : array;  
    
    /**
     * @abstract   Return Widget Parameters Array 
     * 
     * @param      string   $WidgetId           Widgets Type Identifier 
     * @param      array    $Parameters         Updated Parameters 
     * 
     * @return     array
     */    
    public function setWidgetParameters($WidgetId, $Parameters) : bool;  
    
    
    /**
     * @abstract   Return Widget Parameters Fields Array 
     * 
     * @param      string   $WidgetId           Widgets Type Identifier 
     * 
     * @return     array
     */    
    public function getWidgetParametersFields($WidgetId) : array;      
}