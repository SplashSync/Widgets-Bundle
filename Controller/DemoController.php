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
        
        $Widgets        =   $this->get("Splash.Widgets.Listing")->getList(ListingService::DEMO_WIDGETS);
        
        $DemoCollection =   $this->getDemoCollection();
        
        return $this->render('SplashWidgetsBundle:Demo:index.html.twig', array(
            'Widgets'       => $Widgets->getArguments(),
            'Collection'    => $DemoCollection
                ));
    }

    public function singleAction()
    {
        //==============================================================================
        // Init & Safety Check 
        if (!$this->initialize()) {
            return new Response("Splash Widgets : Init Failed", 500);
        }
        
        $Widgets    =   $this->get("Splash.Widgets.Listing")->getList(ListingService::DEMO_WIDGETS);
        
        return $this->render('SplashWidgetsBundle:Demo/Single:index.html.twig', array(
            'Widgets'       => $Widgets->getArguments(),
                ));
    }    
    
    public function collectionAction()
    {
        //==============================================================================
        // Init & Safety Check 
        if (!$this->initialize()) {
            return new Response("Splash Widgets : Init Failed", 500);
        }
        
        $Widgets        =   $this->get("Splash.Widgets.Listing")->getList(ListingService::DEMO_WIDGETS);
        
        $DemoCollection =   $this->getDemoCollection();
        
        return $this->render('SplashWidgetsBundle:Demo/Collection:index.html.twig', array(
            'Widgets'       => $Widgets->getArguments(),
            'Collection'    => $DemoCollection,
            'Edit'          => False
                ));
    }    
    
    public function collection_editAction()
    {
        //==============================================================================
        // Init & Safety Check 
        if (!$this->initialize()) {
            return new Response("Splash Widgets : Init Failed", 500);
        }
        
        $Widgets        =   $this->get("Splash.Widgets.Listing")->getList(ListingService::DEMO_WIDGETS);
        
        $DemoCollection =   $this->getDemoCollection();
        
        return $this->render('SplashWidgetsBundle:Demo/Collection:index.html.twig', array(
            'Widgets'       => $Widgets->getArguments(),
            'Collection'    => $DemoCollection,
            'Edit'          => True
                ));
    }      
    
    public function getDemoCollection()
    {
        //==============================================================================
        // Load Collection 
        $DemoCollection =   $this->get("doctrine")
                ->getManager()
                ->getRepository("SplashWidgetsBundle:WidgetCollection")
                ->findOneByType("demo-collection");
        
        //==============================================================================
        // Create Demo Collection
        if(!$DemoCollection) {
            $DemoCollection = new \Splash\Widgets\Entity\WidgetCollection();
            $DemoCollection
                    ->setName("Bundle Demonstration")
                    ->setType("demo-collection");

            $Widgets    =   $this->get("Splash.Widgets.Listing")->getList(ListingService::DEMO_WIDGETS);            
            foreach ($Widgets->getArguments() as $Widget) {
                $DemoCollection->addWidget($Widget);
            }
            
            $Em = $this->get("doctrine")->getManager();
            $Em->persist($DemoCollection);
            $Em->flush();
        }
        
        return $DemoCollection;
    }
}
