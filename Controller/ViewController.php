<?php

namespace Splash\Widgets\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Splash\Widgets\Models\Widget;

class ViewController extends Controller
{

    /**
     * WidgetFactory Service
     * 
     * @var Splash\Widgets\Services\FactoryService
     */    
    private $Factory;
        
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
     */
    public function forcedAction($Service, $WidgetId, $Options = array() , $Parameters = array())
    {
        //==============================================================================
        // Init & Safety Check 
        if (!$this->initialize()) {
            return new Response("Splash Widgets : Init Failed", 500);
        }

        //==============================================================================
        // Load Widget & Contents 
        $Widget     =   $this->loadWidgetFromInterface($Service, $WidgetId, $Parameters);
        if ( !empty($Options) ) {
            $Widget->setOptions($Options);
        } 
        
        return $this->render('SplashWidgetsBundle:View:forced.html.twig', array(
                "Widget"    => $Widget,
                "Edit"      => False
            ));
    }    
    
    public function viewAction($Service, $WidgetId, $Options = array() , $Parameters = array())
    {
        //==============================================================================
        // Init & Safety Check 
        if (!$this->initialize()) {
            return $this->redirectToRoute("fos_user_security_login");
        }
        
        return $this->render('SplashWidgetsBundle:Render:normal.html.twig', $this->prepare($Service, $WidgetId, $Options, $Parameters));
    }
    
    
    public function indexAction($Service, $WidgetId, $Options = array() , $Parameters = array())
    {
        //==============================================================================
        // Init & Safety Check 
        if (!$this->initialize()) {
            return $this->redirectToRoute("fos_user_security_login");
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
                ->getWidget($WidgetId, $this->User, $Parameters);
        
        if ( empty($Widget) || !is_a($Widget, Widget::class)  ) {
            return $this->buildErrorWidget($Service, $WidgetId, "An Error Occured During Widget Loading");
        }
        
        return $Widget;
    }    
    
  
    public function buildErrorWidget($Service,$WidgetId,$Error)
    {
        $this->Factory
                
            //==============================================================================
            // Create Widget 
            ->Create($WidgetId)
                ->setTitle($Service . " => " . $WidgetId)
            ->end()
                
            //==============================================================================
            // Create Notifications Block 
            ->addBlock("NotificationsBlock")
                ->setError($Error)
            ->end()

        ;
        
        return $this->Factory->getWidget();
    }
    
}
