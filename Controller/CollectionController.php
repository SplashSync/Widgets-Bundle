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
    
}
