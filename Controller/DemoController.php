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
        
        $Widgets    =   $this->get("Splash.Widgets.Listing")->getList(ListingService::DEMO_WIDGETS);
        
        
        
        
        $DemoCollection =   $this->get("doctrine")
                ->getManager()
                ->getRepository("SplashWidgetsBundle:WidgetCollection")
                ->findOneByType("demo-collection");
        
        if(!$DemoCollection) {
            $DemoCollection = new \Splash\Widgets\Entity\WidgetCollection();
            $DemoCollection
                    ->setName("Bundle Demonstration")
                    ->setType("demo-collection");
            
            foreach ($Widgets->getArguments() as $Widget) {
                $DemoCollection->addWidget($Widget);
            }
            
            $Em = $this->get("doctrine")->getManager();
            $Em->persist($DemoCollection);
            $Em->flush();
        }
        
        dump($DemoCollection);
        
        
        return $this->render('SplashWidgetsBundle:Demo:index.html.twig', array(
            'Widgets'       => $Widgets->getArguments(),
            'Collection'    => $DemoCollection
                ));
    }

}
