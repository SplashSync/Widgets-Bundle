<?php

namespace Splash\Widgets\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Splash\Widgets\Entity\Widget;

use Splash\Widgets\Form\WidgetOptionsType;
use Splash\Widgets\Form\WidgetDatesType;


class EditController extends Controller
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
    public function initialize() {
        
        //====================================================================//
        // Get WidgetFactory Service
        $this->Factory = $this->get("Splash.Widgets.Factory");
        
        return True;
    }
   
    //==============================================================================
    // AJAX MODALS
    //==============================================================================
    
    /**
     * @abstract Displays a form to edit a Widget.
     */
    public function modalAction(Request $request, $Service, $Type)
    {
        //==============================================================================
        // Init & Safety Check 
        if (!$this->initialize()) {
            return new Response("Error... ", 400);
        }   
        
        //==============================================================================
        // Import Form Data & Prepare Data for Form Display   
        $Params = $this->prepare($request, $Service, $Type);
//        $Params = array();
        //==============================================================================
        // Render Widget Edit Modal   
        return $this->render('SplashWidgetsBundle:Edit:modal.html.twig', $Params );
    }

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
        // Render Widget Edit Well   
        return $this->render('SplashWidgetsBundle:Edit:well.html.twig', $Params );
    }    
    
    
    //==============================================================================
    // LOW LEVEL FUNCTIONS
    //==============================================================================

    /**
     * @abstract Prepare Data to Display Edit form on a Widget
     */
    public function prepare(Request $request, $Service, $Type)
    {
        //==============================================================================
        // Verify Item Service is Available 
        if ( !$this->has($Service)) {
            return new Response("Requested Widget Service doesn't Exists", 400);
        }        
        
        //==============================================================================
        // Read Current Widget Options 
        $Options =   $this->get($Service)
                ->getWidgetOptions($Type);

        //==============================================================================
        // Read Current Widget Parameters 
        $Parameters =   $this->get($Service)
                ->getWidgetParameters($Type);

        //==============================================================================
        // Read Current Widget Parameters 
//        $Fields =   $this->get($Service)
//                ->getWidgetParametersFields($Type, $this->User);
        
        //==============================================================================
        // Create Widget Object for Form 
        $Widget = $this->Factory
            ->Create($Type)
                ->setOptions($Options)
                ->setParameters($Parameters)
                ->getWidget();
        
        //==============================================================================
        // Create Edit Form
        $this->EditForm = $this->createEditForm($Widget, $Service, $Type);
        
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
                    ->setWidgetOptions($Type, $this->User, $Widget->getOptions());
                $this->get($Service)
                    ->setWidgetParameters($Type, $this->User, $Widget->getParameters());
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
    private function createEditForm(Widget $Widget, $Service, $Type)
    {

        //====================================================================//
        // Create Form Builder
        $FormBuilder =   $this->createFormBuilder($Widget);
        
        $FormBuilder->setAction(
                    $this->generateUrl('splash_widgets_edit',['Service' => $Service, "Type" => $Type])
                );
        
        //====================================================================//
        // Populate Widget Rendering Option Form Tab
        $WidgetOptionsForm = new WidgetOptionsType();
        $WidgetOptionsForm->buildForm($FormBuilder, []);

        //====================================================================//
        // Populate Widget Rendering Option Form Tab
        $WidgetDatesForm = new WidgetDatesType();
        $WidgetDatesForm->buildForm($FormBuilder, []);
        
        //====================================================================//
        // Import Widget Option Form Fields
//        $this->Factory
//                ->populateWidgetForm($FormBuilder, $Fields, True);

                
        return $FormBuilder->getForm();
        
    }
    
}
