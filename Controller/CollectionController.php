<?php

namespace Splash\Widgets\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

use Splash\Widgets\Entity\Widget;

class CollectionController extends Controller
{
    /**
     * @abstract    Widget Collection
     * @var Splash\Widgets\Entity\WidgetCollection
     */    
    private $Collection = Null;    
    
    /**
     * Class Initialisation
     * 
     * @param string    $CollectionId        Widget Collection Id
     * 
     * @return bool 
     */    
    public function initialize($CollectionId = Null) {
        
        //==============================================================================
        // Load Collection 
        $this->Collection =   $this->get("doctrine")
                ->getManager()
                ->getRepository("SplashWidgetsBundle:WidgetCollection")
                ->find($CollectionId);        
        
        return $this->Collection ? True : False;
    }        
    
    /*
     * @abstract    Render Widget Collection
     * 
     * @param   string      $CollectionId           Widget Collection Id
     * 
     */
    public function viewAction($CollectionId)
    {
        //==============================================================================
        // Init & Safety Check 
        if (!$this->initialize($CollectionId)) {
            return new Response("Splash Widgets : Init Failed", 500);
        }
        
        //==============================================================================
        // Render Response 
        return $this->render('SplashWidgetsBundle:View:collection.html.twig', array(
                "Collection"=> $this->Collection,
                "Edit"      => False
            ));
    }    
    
    
    /*
     * @abstract    Render Widget Collection
     * 
     * @param   string      $CollectionId           Widget Collection Id
     * 
     */
    public function editAction($CollectionId)
    {
        //==============================================================================
        // Init & Safety Check 
        if (!$this->initialize($CollectionId)) {
            return new Response("Splash Widgets : Init Failed", 500);
        }
        
        //==============================================================================
        // Render Response 
        return $this->render('SplashWidgetsBundle:View:collection.html.twig', array(
                "Collection"=> $this->Collection,
                "Edit"      => True
            ));
    }      
    
    /**
     * @abstract Update Collection Widget Ordering from Ajax Request
     */  
    public function reorderAction($CollectionId,$Ordering)
    {
        //==============================================================================
        // Init & Safety Check 
        if (!$this->initialize($CollectionId)) {
            return new Response("Splash Widgets : Init Failed", 500);
        }
        //==============================================================================
        // Decode Json Value 
        $OrderArray = json_decode($Ordering);
        //==============================================================================
        // Apply
        if ( !$this->Collection->reorder($OrderArray) ) {
            return new Response("Widget Collection ReOrder Failled", 400);
        }        
        //==============================================================================
        // Save Changes
        $this->getDoctrine()->getManager()->Flush();
        
        return new Response("Widget Collection ReOrder Done", 200);
    }    
    
    /**
     * @abstract Update Collection Dates Preset from Ajax Request
     */  
    public function presetAction($CollectionId,$Preset = "M")
    {
        //==============================================================================
        // Init & Safety Check 
        if (!$this->initialize($CollectionId)) {
            return new Response("Splash Widgets : Init Failed", 500);
        }
        
        //==============================================================================
        // Update CVollection Itself
        $this->Collection->setPreset($Preset);
        foreach ($this->Collection->getWidgets() as $Widget) {
            if (!$Widget->isPreset($Preset)) {
                continue;
            }
            
            $this
                    ->get("Splash.Widgets.Manager")
                    ->setWidgetParameter(
                            $this->Collection->getService(),
                            $Widget->getId() . "@" . $this->Collection->getid(),
                            "DatePreset",
                            $Preset
                            )
                    ;
        }
        //==============================================================================
        // Save Changes
        $this->getDoctrine()->getManager()->Flush();
        
        return new Response("Widget Collection Dates Preset Updated", 200);
    }        
    
    /**
     * @abstract Add Widget to Collection from Ajax Request
     */  
    public function addAction($CollectionId, $Service, $Type)
    {
        //==============================================================================
        // Init & Safety Check 
        if (!$this->initialize($CollectionId)) {
            return new Response("Splash Widgets : Init Failed", 500);
        }
        //==============================================================================
        // Load Widget 
        $Widget =   $this->get("Splash.Widgets.Manager")->getWidget($Service, $Type);
        if (is_null($Widget)  ) {
            return new Response("Widget NOT Added to Collection", 400);
        }   
        if ( $this->Collection->getPreset()  ) {
            $Widget->setParameter("DatePreset" , $this->Collection->getPreset());
        }   
        //==============================================================================
        // Add Widget To Collection
        $this->Collection->addWidget($Widget);
        //==============================================================================
        // Save Changes
        $this->getDoctrine()->getManager()->Flush();
        
        return new Response("Widget Added to Collection : " . $this->Collection->getName(), 200);
    }     
    
    /**
     * @abstract Remove Widget from Collection from Ajax Request
     */  
    public function removeAction($Service, $Type)
    {
        //==============================================================================
        // Init & Safety Check 
        if ($this->has($Service)) {
            $CollectionManager = $this->get($Service);
        } else {
            $CollectionManager = $this->get("Splash.Widgets.Collection");
        }
        if (!$CollectionManager) {
            return new Response("Splash Widgets : Init Failed", 500);
        }
        //==============================================================================
        // Load Widget from Collection
        $Widget =   $CollectionManager->getDefinition($Type);
        if (is_null($Widget)  ) {
            return new Response("Widget NOT Removed to Collection", 400);
        }        
        //==============================================================================
        // Add Widget To Collection
        $Widget->getParent()->removeWidget($Widget);
        $this->getDoctrine()->getManager()->Remove($Widget);
        //==============================================================================
        // Save Changes
        $this->getDoctrine()->getManager()->Flush();
        return new Response("Widget Removed to Collection : " . $Widget->getParent()->getName(), 200);
    }    
}
