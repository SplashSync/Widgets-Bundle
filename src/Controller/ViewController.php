<?php

namespace Splash\Widgets\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Splash\Widgets\Entity\Widget;
use Splash\Widgets\Entity\WidgetCache;

class ViewController extends Controller
{


    
    /*
     * @abstract    Render Widget without Using Cache & Ajax Loading
     * 
     * @param   string      $Service        Widget Provider Service Name
     * @param   string      $Type           Widget Type Name
     * @param   string      $Options        Widget Rendering Options
     * @param   string      $Parameters     Widget Parameters
     */
    public function forcedAction($Service, $Type, $Options = Null, $Parameters = Null)
    {
        //==============================================================================
        // Decode Widget Parameters
        $WidgetParameters     = is_null($Parameters)  ? array() : json_decode($Parameters, True);
        
        //==============================================================================
        // Read Widget Contents 
        $Widget =   $this->get("Splash.Widgets.Manager")->getWidget($Service, $Type, $WidgetParameters);
        
        //==============================================================================
        // Validate Widget Contents 
        if (is_null($Widget)  ) {
            $Widget =   $this->get("Splash.Widgets.Factory")->buildErrorWidget($Service, $Type, "An Error Occured During Widget Loading");
        }
        
        //==============================================================================
        // Setup Widget Options 
        if ( !is_null($Options) && !empty(json_decode($Options, True)) ) {
            $Widget->mergeOptions( json_decode($Options, True) );
        }     
        
        //==============================================================================
        // Render Response 
        return $this->render('SplashWidgetsBundle:Widget:base.html.twig', array(
                "Widget"    =>  $Widget,
                "WidgetId"  =>  WidgetCache::buildDiscriminator($Widget->getOptions(), $Widget->getParameters()),
            ));
    }  
    
    /*
     * @abstract    Render Widget Using Cache & Ajax Loading
     * 
     * @param   string      $Service        Widget Provider Service Name
     * @param   string      $Type           Widget Type Name
     * @param   string      $Options        Widget Rendering Options
     * @param   string      $Parameters     Widget Parameters
     * 
     */    
    public function delayedAction($Service, $Type, $Options = Null, $Parameters = Null)
    {
        //==============================================================================
        // Fetch Widget Options
        if ( is_null($Options) || empty(json_decode($Options, True)) ) {
            if ( !$Service) {
                $WidgetOptions = Widget::getDefaultOptions();
            } else {
                $WidgetOptions = $this->get("Splash.Widgets.Manager")->getWidgetOptions($Service, $Type);
            }
        } else {
            $WidgetOptions = json_decode($Options, True);
        }     
        
        //==============================================================================
        // Decode Widget Parameters
        $WidgetParameters     = is_null($Parameters)  ? array() : json_decode($Parameters, True);       
        
        //==============================================================================
        // Load From cache if Available 
        $Cache  =  $this->get("Splash.Widgets.Manager")->getCache($Service,$Type, $WidgetOptions, $WidgetParameters);
        if($Cache) {
            //==============================================================================
            // Setup Widget Options 
            $Cache->mergeOptions( $WidgetOptions );
            //==============================================================================
            // Render Cached Widget 
            return $this->render('SplashWidgetsBundle:Widget:base.html.twig', array(
                    "Widget"        =>  $Cache,
                    "WidgetId"      =>  WidgetCache::buildDiscriminator($WidgetOptions, $WidgetParameters),
                    "Options"       =>  $WidgetOptions,
                ));
        }
        
        //==============================================================================
        // Render Loading Widget Box 
        return $this->render('SplashWidgetsBundle:View:delayed.html.twig', array(
                "Service"       =>  $Service,
                "WidgetType"    =>  $Type,
                "WidgetId"      =>  WidgetCache::buildDiscriminator($WidgetOptions, $WidgetParameters),
                "Options"       =>  $WidgetOptions,
                "Parameters"    =>  $Parameters,
            ));
    }
     
    /*
     * @abstract    Render Widget Contents
     * 
     * @param   string      $Service        Widget Provider Service Name
     * @param   string      $Type           Widget Type Name
     * @param   string      $Options        Widget Rendering Options
     * @param   string      $Parameters     Widget Parameters
     * 
     */       
    public function ajaxAction($Service, $Type, $Options = Null, $Parameters = Null)
    {
        //==============================================================================
        // Decode Widget Parameters
        $WidgetParameters     = is_null($Parameters)  ? array() : json_decode($Parameters, True);        
        
        //==============================================================================
        // Read Widget Contents 
        $Widget =   $this->get("Splash.Widgets.Manager")->getWidget($Service, $Type, $WidgetParameters);
        
        //==============================================================================
        // Fetch Widget Options
        if ( is_null($Options) || empty(json_decode($Options, True)) ) {
            $WidgetOptions = Widget::getDefaultOptions();
        } else {
            $WidgetOptions = json_decode($Options, True);
        }    
        
        //==============================================================================
        // Validate Widget Contents 
        if ( empty($Widget) || !is_a($Widget, Widget::class)  ) {
            $Widget =   $this->get("Splash.Widgets.Factory")->buildErrorWidget($Service, $Type, "An Error Occured During Widget Loading");
            return $this->render('SplashWidgetsBundle:Widget:contents.html.twig', array(
                    "WidgetId"  => WidgetCache::buildDiscriminator($WidgetOptions, $WidgetParameters),
                    "Widget"    =>  $Widget,
                    "Options"   =>  $WidgetOptions,
                ));
        }
        
        //==============================================================================
        // Setup Widget Options 
        if ( !is_null($Options) && !empty(json_decode($Options, True)) ) {
            $Widget->mergeOptions( json_decode($Options, True) );
        }
        
        //==============================================================================
        // Update Cache 
        if( !$WidgetOptions["EditMode"]) {
            //==============================================================================
            // Generate Widget Raw Contents 
            $Contents = $this->renderView('SplashWidgetsBundle:Widget/Blocks:row.html.twig', array(
                    "Widget"    => $Widget,
                    "Options"   => $WidgetOptions,
                ));
            $this->get("Splash.Widgets.Manager")->setCacheContents($Widget, $Contents);
        }

        //==============================================================================
        // Render Widget Contents 
        return $this->render('SplashWidgetsBundle:Widget:contents.html.twig', array(
                "WidgetId"      => WidgetCache::buildDiscriminator($WidgetOptions, $WidgetParameters),
                "Widget"        => $Widget,
                "Options"       => $WidgetOptions,
            ));
    }
    
}
