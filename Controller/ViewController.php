<?php

namespace Splash\Widgets\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Splash\Widgets\Entity\Widget;

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
     * @param string    $Service        Widget Provider Interface Name
     * 
     * @return bool 
     */    
    public function initialize($Service = Null) {
        
        $this->Factory = $this->get("Splash.Widgets.Factory");
        
        //==============================================================================
        // Link to Widget Interface Service if Available 
        if ( $Service && $this->has($Service)) {
            $this->Service = $this->get($Service);
        }

        return True;
    }        
    
    /*
     * @abstract    Render Widget without Using Cache & Ajax Loading
     */
    public function forcedAction($Service, $WidgetId, $Options = array() , $Parameters = array())
    {
        //==============================================================================
        // Init & Safety Check 
        if (!$this->initialize($Service)) {
            return new Response("Splash Widgets : Init Failed", 500);
        }
        //==============================================================================
        // Read Widget Contents 
        if ( $this->Service ) {
            $Widget =   $this->Service->getWidget($WidgetId, $Parameters);
        } 
        //==============================================================================
        // Validate Widget Contents 
        if ( empty($Widget) || !is_a($Widget, Widget::class)  ) {
            $Widget =   $this->Factory->buildErrorWidget($Service, $WidgetId, "An Error Occured During Widget Loading");
        }
        //==============================================================================
        // Overide Widget Options 
        if ( !empty($Options) ) {
            $Widget->setOptions($Options);
        } 
        //==============================================================================
        // Render Response 
        return $this->render('SplashWidgetsBundle:View:forced.html.twig', array(
                "Widget"    => $Widget,
                "Edit"      => False
            ));
    }    
    
    public function delayedAction($Service, $WidgetId, $Options = array() , $Parameters = array())
    {
        //==============================================================================
        // Init & Safety Check 
        if (!$this->initialize($Service)) {
            return $this->redirectToRoute("fos_user_security_login");
        }
        
        //==============================================================================
        // Verify Interface Service is Available 
        if ( !$this->Service) {
            $Options = Widget::OPTIONS;
        } else {
            $Options = $this->Service->getWidgetOptions($WidgetId);
        }
        
        return $this->render('SplashWidgetsBundle:View:delayed.html.twig', array(
                "Service"       =>  $Service,
                "WidgetId"      =>  $WidgetId,
                "Options"       =>  $Options,
            ));
    }
    
    
    public function indexAction($Service, $WidgetId, $Options = array() , $Parameters = array())
    {
        //==============================================================================
        // Init & Safety Check 
        if (!$this->initialize()) {
            return new Response("Splash Widgets : Init Failed", 500);
        }

        
        return $this->render('SplashWidgetsBundle:Render:index.html.twig', $this->prepare($Service, $WidgetId, $Options, $Parameters));
    }
    
    
    public function prepare($Service, $WidgetId, $Options = array() , $Parameters = array(), $Edit = False)
    {
        
        $Widget     =   $this->loadWidgetFromInterface($Service, $WidgetId, $Parameters);
        $Widget->setOptions($Options);
        
        return array(
            "Widget"    => $Widget,
            "Edit"      => $Edit
                );
    }    
    
    public function loadWidgetFromInterface($Service, $WidgetId, $Parameters)
    {
        //==============================================================================
        // Verify Item Service is Available 
        if ( !$this->has($Service)) {
            return $this->buildErrorWidget($Service, $WidgetId, "Requested Service doesn't Exists");
        }
        //==============================================================================
        // Read Widget Contents 
        $Widget =   $this->get($Service)
                ->getWidget($WidgetId, $Parameters);
        
        if ( empty($Widget) || !is_a($Widget, Widget::class)  ) {
            return $this->Factory->buildErrorWidget($Service, $WidgetId, "An Error Occured During Widget Loading");
        }
        
        return $Widget;
    }    
    
  
    
}
