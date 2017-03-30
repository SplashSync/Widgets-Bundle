<?php

namespace Splash\Widgets\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Splash\Widgets\Models\Widget;

class ConfigController extends Controller
{
    /**
     * Current User
     * @var User         User
     */    
    private $User;

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
    public function initialize() {
        //==============================================================================
        // Load User Data
        $this->User = $this->getUser();
        //==============================================================================
        // Safety Check 
        if (is_null($this->User)) {
            return new Response('You are Not Logged In!', 400);
        }
        
        //====================================================================//
        // Get WidgetFactory Service
        $this->Factory = $this->get("OpenWidgets.Core.Factory");
        
        return True;
    }
   
    //==============================================================================
    // AJAX MODALS
    //==============================================================================
    
//    /**
//     * Displays a form to edit a Report Widget.
//     *
//     */
//    public function modalAction(Request $request, $ReportId, $WidgetId)
//    {
//        //==============================================================================
//        // Init & Safety Check 
//        if (!$this->initialize()) {
//            return new Response("You are not logged... ", 400);
//        }   
//        
//        //==============================================================================
//        // Import Form Data & Prepare Data for Form Display   
//        $Params = $this->prepare($request, $ReportId, $WidgetId);
//        
//        //==============================================================================
//        // Render Report Widget Edit Modal   
//        return $this->render('OpenWidgetsReportsBundle:WidgetParameters:modal.html.twig', $Params );
//    }

    //==============================================================================
    // NON-AJAX ACTIONS
    //==============================================================================
    
    /**
     * @abstract Displays a form to edit a Widget.
     */
    public function wellAction(Request $request, $Service, $WidgetId)
    {
        //==============================================================================
        // Init & Safety Check 
        if ($this->initialize() != True) {
            return new Response("You are not logged... ", 400);
        }
        
        //==============================================================================
        // Import Form Data & Prepare Data for Form Display   
        $Params = $this->prepare($request, $Service, $WidgetId);
        
        //==============================================================================
        // Render Report Widget Edit Modal   
        return $this->render('SplashWidgetsBundle:Config:well.html.twig', $Params );
    }    
    
    
    //==============================================================================
    // LOW LEVEL FUNCTIONS
    //==============================================================================

    /**
     * @abstract Prepare Data to Display Edit form on a Widget
     */
    public function prepare(Request $request, $Service, $WidgetId)
    {
        //==============================================================================
        // Verify Item Service is Available 
        if ( !$this->has($Service)) {
            return new Response("Requested Widget Service doesn't Exists", 400);
        }        
        
        //==============================================================================
        // Read Current Widget Options 
        $Options =   $this->get($Service)
                ->getWidgetOptions($WidgetId, $this->User);

        //==============================================================================
        // Read Current Widget Parameters 
        $Parameters =   $this->get($Service)
                ->getWidgetParameters($WidgetId, $this->User);

        //==============================================================================
        // Read Current Widget Parameters 
        $Fields =   $this->get($Service)
                ->getWidgetParametersFields($WidgetId, $this->User);
        
        //==============================================================================
        // Create Widget Object for Form 
        $Widget = $this->Factory
            ->Create($WidgetId)
                ->setOptions($Options)
                ->setParameters($Parameters);
        
        //==============================================================================
        // Create Edit Form
        $this->EditForm = $this->createEditForm($Widget, $Fields);
        
        //==============================================================================
        // Handle User Posted Data
        $this->EditForm->handleRequest($request);
        
//        if ($request->isMethod('GET')) {
//            $this->EditForm->submit($request->request->get($this->EditForm->getName()));
        
//        dump($request->request->get($this->EditForm->getName()));
////        dump($this->EditForm);
//        dump($this->EditForm->isSubmitted());
//        dump($this->EditForm->isValid());
//        dump($Widget->getParameters());
        
        
            //==============================================================================
            // Verify Data is Valid
            if ( $this->EditForm->isSubmitted() && $this->EditForm->isValid() ) {
                //==============================================================================
                // Save Changes
                $this->get($Service)
                    ->setWidgetOptions($WidgetId, $this->User, $Widget->getOptions());
                $this->get($Service)
                    ->setWidgetParameters($WidgetId, $this->User, $Widget->getParameters());
            }
//        }
        
        //==============================================================================
        // Prepare Template Parameters
        return array(
                'Widget'        => $Widget,
                'Form'          => $this->EditForm->createView()
            );
    }
    
    /**
    * Creates a form to edit a Remote Widget/Item Parameters.
    *
    * @param WsWidget     $Widget     The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Widget $Widget, $Fields)
    {
        //====================================================================//
        // Create Form Builder
        $FormBuilder =   $this->createFormBuilder($Widget);
        
        //====================================================================//
        // Import Widget Option Form Fields
        $this->Factory
                ->populateWidgetForm($FormBuilder, $Fields, True);
        
        return $FormBuilder->getForm();
        
    }
    
}
