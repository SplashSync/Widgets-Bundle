<?php

namespace Splash\Widgets\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class ListController extends Controller
{
    
    /**
     * @abstract    Class Initialization
     * @return bool 
     */    
    public function initialize() {
        return True;
    }    
    
    //==============================================================================
    // PANEL
    //==============================================================================
    
    /**
     * @abstract    Render List of Collection available Widgets
     * 
     * @param int       $CollectionId   Widgets Collection Identifier
     * @param string    $Channel        Widgets Listening Channel Name
     */
    public function panelAction(Request $request, int $CollectionId, string $Channel)
    {
        //==============================================================================
        // Init & Safety Check 
        if (!$this->initialize()) {
            return new Response("You are not logged... ", 400);
        }   
        
        //==============================================================================
        // Read List of Available Widgets & Prepare Response Array 
        $Params = $this->prepare($CollectionId, $Channel);
        
        //==============================================================================
        // Render Panel List  
        return $this->render('SplashWidgetsBundle:List:panel.html.twig', $Params );
    }
    
    
    
    //==============================================================================
    // AJAX MODALS
    //==============================================================================
    
    /**
     * @abstract    Render Modal List of Collection available Widgets
     * 
     * @param int       $CollectionId   Widgets Collection Identifier
     * @param string    $Channel        Widgets Listening Channel Name
     */
    public function modalAction(Request $request, int $CollectionId, string $Channel)    
    {
        //==============================================================================
        // Init & Safety Check 
        if (!$this->initialize()) {
            return new Response("You are not logged... ", 400);
        }   
        //==============================================================================
        // Import Form Data & Prepare Data for Form Display   
        $Params = $this->prepare($CollectionId, $Channel);
        //==============================================================================
        //Render Modal List  
        return $this->render('SplashWidgetsBundle:List:modal.html.twig', $Params );
    }
    
    
    /**
     * @abstract Read List of Available Widgets & Prepare Response Array 
     * 
     * @param int       $CollectionId   Widgets Collection Identifier
     * @param string    $Channel        Widgets Listening Channel Name
     */
    public function prepare(int $CollectionId, string $Channel)
    {
        
        //==============================================================================
        // Get List of Widgets
        $Widgets = $this->get("Splash.Widgets.Manager")->getList( "splash.widgets.list." . $Channel);
        
        $Tabs = array();
        foreach ($Widgets as $Key => $Widget) {
            $TabId = md5(base64_encode($Widget->getOrigin()));
            //==============================================================================
            // Create Tab
            if (!isset($Tabs[ $TabId ])) {
                $Tabs[$TabId] = array(
                    "label"     =>  $Widget->getOrigin(),
                    "id"        =>  $TabId,
                    "widgets"   =>  [$Key]
                );
            //==============================================================================
            // Add To Tab
            } else {
                $Tabs[$TabId]["widgets"][] = $Key;
            }
        }
        
        dump($Tabs);
        dump($Widgets);
        
        //==============================================================================
        // Prepare Rendering Parameters
        return array(
                'CollectionId'  =>  $CollectionId,
                'Widgets'       =>  $Widgets,
                'Tabs'          =>  $Tabs,
            );
    }
    
}
