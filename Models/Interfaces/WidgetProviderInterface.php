<?php

namespace Splash\Widgets\Models\Interfaces;

use UserBundle\Entity\User;

/*
 * @abstract    Widget provider Interface
 */
interface WidgetProviderInterface 
{
    
    /**
     * @abstract   Read Widget Contents
     * 
     * @param      string   $WidgetId           Widgets Type Identifier 
     * @param      User     $User               Current User 
     * 
     * @return     Widget 
     */    
    public function getWidget($WidgetId, User $User = Null);

    /**
     * @abstract   Return Widget Options Array 
     * 
     * @param      string   $WidgetId           Widgets Type Identifier 
     * @param      User     $User               Current User 
     * 
     * @return     array
     */    
    public function getWidgetOptions($WidgetId, User $User) : array;    

    /**
     * @abstract   Return Widget Parameters Array 
     * 
     * @param      string   $WidgetId           Widgets Type Identifier 
     * @param      User     $User               Current User 
     * 
     * @return     array
     */    
    public function getWidgetParameters($WidgetId, User $User) : array;  
    
    /**
     * @abstract   Return Widget Parameters Array 
     * 
     * @param      string   $WidgetId           Widgets Type Identifier 
     * @param      User     $User               Current User 
     * @param      array    $Parameters         Updated Parameters 
     * 
     * @return     array
     */    
    public function setWidgetParameters($WidgetId, User $User, $Parameters) : bool;  
    
    
    /**
     * @abstract   Return Widget Parameters Fields Array 
     * 
     * @param      string   $WidgetId           Widgets Type Identifier 
     * @param      User     $User               Current User 
     * 
     * @return     array
     */    
    public function getWidgetParametersFields($WidgetId, User $User) : array;      
}