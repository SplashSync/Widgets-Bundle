<?php

namespace Splash\Widgets\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Splash\Widgets\Entity\Widget;

use Symfony\Component\HttpFoundation\Response;

class ViewController extends Controller
{

    /**
     * WidgetFactory Service
     * 
     * @var Splash\Widgets\Services\FactoryService
     */    
    private $Factory;

    /**
     * WidgetInterface Service
     * 
     * @var Splash\Widgets\Models\Interfaces\WidgetProviderInterface
     */    
    private $Service = Null;

    /**
     * Class Initialisation
     * 
     * @return bool 
     */    
    public function initialize() {
        
        $this->Factory = $this->get("Splash.Widgets.Factory");
        
        return True;
    }        
    
    /*
     * @abstract    Render Widget without Using Cache & Ajax Loading
     * 
     * @param   string      $Service        Widget Provider Service Name
     * @param   string      $Type           Widget Type Name
     * @param   string      $Edit           Widget Edit Mode
     * @param   array       $Options        Override Widget Options
     * @param   array       $Parameters     Override Widget $Parameters
     * 
     */
    public function forcedAction($Service, $Type, $Edit = False, $Options = array() , $Parameters = array())
    {
        //==============================================================================
        // Init & Safety Check 
        if (!$this->initialize()) {
            return new Response("Splash Widgets : Init Failed", 500);
        }
        //==============================================================================
        // Read Widget Contents 
        $Widget =   $this->get("Splash.Widgets.Manager")->getWidget($Service, $Type, $Parameters);
        //==============================================================================
        // Validate Widget Contents 
        if (is_null($Widget)  ) {
            $Widget =   $this->Factory->buildErrorWidget($Service, $Type, "An Error Occured During Widget Loading");
        }
        //==============================================================================
        // Overide Widget Type
        $Widget->setType(uniqid($Widget->getType()));
        //==============================================================================
        // Overide Widget Options 
        if ( !empty($Options) ) {
            $Widget->setOptions($Options);
        } 
        //==============================================================================
        // Render Response 
        return $this->render('SplashWidgetsBundle:Widget:base.html.twig', array(
                "Widget"    => $Widget,
                "Edit"      => $Edit
            ));
    }    
    
    /*
     * @abstract    Render Widget Using Cache & Ajax Loading
     * 
     * @param   string      $Service        Widget Provider Service Name
     * @param   string      $Type           Widget Type Name
     * @param   string      $Edit           Widget Edit Mode
     * @param   array       $Options        Override Widget Options
     * @param   array       $Parameters     Override Widget $Parameters
     * 
     */    
    public function delayedAction($Service, $Type, $Edit = False, $Options = array() , $Parameters = array())
    {
        //==============================================================================
        // Init & Safety Check 
        if (!$this->initialize()) {
            return new Response("Splash Widgets : Init Failed", 500);
        }
        //==============================================================================
        // Verify Interface Service is Available 
        if ( !$Service) {
            $Options = Widget::getDefaultOptions();
        } else {
            $Options = $this->get("Splash.Widgets.Manager")->getWidgetOptions($Service, $Type);
        }
        //==============================================================================
        // Load From cache if Available 
        if(!$Edit) {
            $Cache  =  $this->get("Splash.Widgets.Manager")->getCache($Service,$Type);
        }
        //==============================================================================
        // Render Loading Widget Box 
        return $this->render('SplashWidgetsBundle:View:delayed.html.twig', array(
                "Service"       =>  $Service,
                "WidgetType"    =>  $Type,
                "Edit"          =>  $Edit,
                "Options"       =>  $Options,
                "Cache"         =>  (isset($Cache) ? $Cache : Null),
            ));
    }
     
    /*
     * @abstract    Render Widget Contents
     * 
     * @param   string      $Service        Widget Provider Service Name
     * @param   string      $Type           Widget Type Name
     * @param   string      $Edit           Widget Edit Mode
     * @param   array       $Options        Override Widget Options
     * @param   array       $Parameters     Override Widget $Parameters
     * 
     */       
    public function ajaxAction($Service, $Type, $Edit = False, $Options = array() , $Parameters = array())
    {
        //==============================================================================
        // Init & Safety Check 
        if (!$this->initialize()) {
            return new Response("Splash Widgets : Init Failed", 500);
        }
        //==============================================================================
        // Read Widget Contents 
        $Widget =   $this->get("Splash.Widgets.Manager")->getWidget($Service, $Type, $Parameters);
        //==============================================================================
        // Validate Widget Contents 
        if ( empty($Widget) || !is_a($Widget, Widget::class)  ) {
            $Widget =   $this->Factory->buildErrorWidget($Service, $Type, "An Error Occured During Widget Loading");
        }
        //==============================================================================
        // Overide Widget Options 
        if ( !empty($Options) ) {
            $Widget->setOptions($Options);
        }
        //==============================================================================
        // Generate Widget Raw Contents 
        $Contents = $this->renderView('SplashWidgetsBundle:Widget:contents.html.twig', array(
                "Widget"    => $Widget,
                "Edit"      => $Edit
            ));
        //==============================================================================
        // Update Cache 
        if(!$Edit) {
            $this->get("Splash.Widgets.Manager")->setCacheContents($Widget, $Contents);
        }
        
        return new Response($Contents);
    }
    
}
