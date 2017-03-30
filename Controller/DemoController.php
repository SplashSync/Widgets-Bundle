<?php

namespace Splash\Widgets\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Splash\Widgets\Model\WidgetBlock    as  Block;

use Splash\Widgets\Services\ListingService;

use Symfony\Component\HttpFoundation\Response;

class DemoController extends Controller
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
        
        //====================================================================//
        // Get WidgetFactory Service
        $this->Factory = $this->get("Splash.Widgets.Factory");
        
        return True;
    }        
    
    
    public function indexAction()
    {
        //==============================================================================
        // Init & Safety Check 
        if (!$this->initialize()) {
            return new Response("Splash Widgets : Init Failed", 500);
        }
        
        $WidgetsList    =   $this->get("Splash.Widgets.Listing")->getList(ListingService::DEMO_WIDGETS);
        
        return $this->render('SplashWidgetsBundle:Demo:index.html.twig', array('Widgets' => $WidgetsList->getArguments()));
    }

}
