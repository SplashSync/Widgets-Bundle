<?php

namespace Splash\Widgets\Models\Interfaces;

use Symfony\Component\Form\FormBuilderInterface;

/*
 * @abstract    Widget provider Interface
 */
interface WidgetProviderInterface 
{
    
    /**
     * @abstract   Read Widget Contents
     * 
     * @param      string   $Type               Widgets Type Identifier 
     * 
     * @return     Widget 
     */    
    public function getWidget($Type);

    /**
     * @abstract   Return Widget Options Array 
     * 
     * @param      string   $Type               Widgets Type Identifier 
     * 
     * @return     array
     */    
    public function getWidgetOptions($Type) : array;    

    /**
     * @abstract   Update Widget Options Array 
     * 
     * @param      string   $Type               Widgets Type Identifier 
     * @param      array    $Options            Updated Options 
     * 
     * @return     array
     */    
    public function setWidgetOptions($Type, $Options) : bool;  
    
    /**
     * @abstract   Return Widget Parameters Array 
     * 
     * @param      string   $Type               Widgets Type Identifier 
     * 
     * @return     array
     */    
    public function getWidgetParameters($Type) : array;  
    
    /**
     * @abstract   Return Widget Parameters Array 
     * 
     * @param      string   $Type               Widgets Type Identifier 
     * @param      array    $Parameters         Updated Parameters 
     * 
     * @return     array
     */    
    public function setWidgetParameters($Type, $Parameters) : bool;  
    
    
    /**
     * @abstract   Return Widget Parameters Fields Array 
     * 
     * @param FormBuilderInterface  $builder
     * @param      string           $Type           Widgets Type Identifier 
     * 
     * @return     array
     */    
    public function populateWidgetForm(FormBuilderInterface $builder, $Type);      
}