<?php

namespace Splash\Widgets\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Splash\Widgets\Services\ManagerService;

class DemoController extends Controller
{
    /**
     * WidgetFactory Service
     * 
     * @var Splash\Widgets\Services\FactoryService
     */    
    private $Factory;
    
    /**
     * Class Initialization
     * 
     * @return bool 
     */    
    public function Initialize() {
        
        //====================================================================//
        // Get WidgetFactory Service
        $this->Factory = $this->get("Splash.Widgets.Factory");
        
        return True;
    }        
    
    
    public function indexAction()
    {
        //==============================================================================
        // Init & Safety Check 
        $this->Initialize();
        
        $Widgets        =   $this->get("Splash.Widgets.Manager")->getList(ManagerService::DEMO_WIDGETS);
        
        $DemoCollection =   $this->getDemoCollection();
        
        return $this->render('@SplashWidgets/Demo/index.html.twig', array(
            'Widgets'       => $Widgets,
            'Collection'    => $DemoCollection
                ));
    }

    public function listAction()
    {
        //==============================================================================
        // Init & Safety Check 
        $this->Initialize();
        
        $Widgets        =   $this->get("Splash.Widgets.Manager")->getList(ManagerService::DEMO_WIDGETS);
        
        return $this->render('@SplashWidgets/Demo/List/index.html.twig', array(
            'Widgets'       => $Widgets,
                ));
    }
    
    public function forcedAction()
    {
        return $this->render('@SplashWidgets/Demo/Single/forced.html.twig', array(
            'Widgets'       => $this->get("Splash.Widgets.Manager")->getList(ManagerService::DEMO_WIDGETS),
                ));
    }    
    
    public function delayedAction()
    {
        return $this->render('@SplashWidgets/Demo/Single/delayed.html.twig', array(
            'Widgets'       => $this->get("Splash.Widgets.Manager")->getList(ManagerService::DEMO_WIDGETS),
                ));
    }         
    
    public function editAction()
    {
        return $this->render('@SplashWidgets/Demo/Single/edit.html.twig', array());
    } 
    
    public function collectionAction()
    {
        //==============================================================================
        // Init & Safety Check 
        $this->Initialize();
        
        $Widgets        =   $this->get("Splash.Widgets.Manager")->getList(ManagerService::DEMO_WIDGETS);
        
        $DemoCollection =   $this->getDemoCollection();
        
        return $this->render('@SplashWidgets/Demo/Collection/index.html.twig', array(
            'Widgets'       => $Widgets,
            'Collection'    => $DemoCollection,
            'Edit'          => False
                ));
    }    
    
    public function collection_editAction()
    {
        //==============================================================================
        // Init & Safety Check 
        $this->Initialize();

        
        $Widgets        =   $this->get("Splash.Widgets.Manager")->getList(ManagerService::DEMO_WIDGETS);
        
        $DemoCollection =   $this->getDemoCollection();
        
        return $this->render('@SplashWidgets/Demo/Collection/index.html.twig', array(
            'Widgets'       => $Widgets,
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

            $Widgets    =   $this->get("Splash.Widgets.Manager")->getList(ManagerService::DEMO_WIDGETS);     
            
            foreach ($Widgets as $Widget) {
                $DemoCollection->addWidget($Widget);
            }
            
            $Em = $this->get("doctrine")->getManager();
            $Em->persist($DemoCollection);
            $Em->flush();
        }
        
        return $DemoCollection;
    }
    
}
