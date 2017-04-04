<?php

namespace Splash\Widgets\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Splash\Widgets\Entity\Widget;

use Symfony\Component\Form\Extension\Core\Type\FormType;
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
     * WidgetInterface Service
     * 
     * @var Splash\Widgets\Models\Interfaces\WidgetProviderInterface
     */    
    private $Service = Null;

    /**
     * Class Initialisation
     * 
     * @param string    $Service        Widget Provider Interface Name
     * 
     * @return bool 
     */    
    public function initialize($Service = Null) {
        
        $this->Factory = $this->get("Splash.Widgets.Factory");
        
        //==============================================================================
        // Link to Widget Interface Service if Available 
        if ( $Service && $this->has($Service)) {
            $this->Service = $this->get($Service);
        }

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
        if (!$this->initialize($Service)) {
            return new Response("Error... ", 400);
        }   
        
        //==============================================================================
        // Import Form Data & Prepare Data for Form Display   
        $Params = $this->prepare($request, $Service, $Type);
//        $Params = array();
//        
//        $FormBuilder->setAction(
//                    $this->generateUrl('splash_widgets_edit',['Service' => $Service, "Type" => $Type])
//                );
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
    public function panelAction(Request $request, $Service, $Type)
    {
        //==============================================================================
        // Init & Safety Check 
        if ($this->initialize($Service) != True) {
            return new Response("Error... ", 400);
        }
        
        //==============================================================================
        // Import Form Data & Prepare Data for Form Display   
        $Params = $this->prepare($request, $Service, $Type);
        
        //==============================================================================
        // Render Widget Edit Well   
        return $this->render('SplashWidgetsBundle:Edit:panel.html.twig', $Params );
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
        $Options =   $this->Service->getWidgetOptions($Type);

        //==============================================================================
        // Read Current Widget Parameters 
        $Parameters =   $this->Service->getWidgetParameters($Type);

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
        // Ajax => Setup Action for Ajax Submit
        if ($request->isXmlHttpRequest()) {
            $Action = $this->generateUrl('splash_widgets_edit',['Service' => $Service, "Type" => $Type]);
        } else {
            $Action = Null;
        }
        
        //==============================================================================
        // Create Edit Form
        $this->EditForm = $this->createEditForm($Widget, $Action);
        
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
                $this->Service->setWidgetOptions($Type, $Widget->getOptions());
                $this->Service->setWidgetParameters($Type, $Widget->getParameters());
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
    private function createEditForm(Widget $Widget, $Action = Null)
    {
        //====================================================================//
        // Create Form Builder
        $builder =   $this->get('form.factory')
            ->createNamedBuilder('splash_widgets_settings_form', FormType::class, $Widget);
        if ( $Action ) {
            $builder->setAction($Action);
        }
        
        //====================================================================//
        // Populate Widget Rendering Option Form Tab
        $WidgetOptionsForm = new WidgetOptionsType();
        $WidgetOptionsForm->buildForm($builder, []);

        //====================================================================//
        // Populate Widget Rendering Option Form Tab
        $WidgetDatesForm = new WidgetDatesType();
        $WidgetDatesForm->buildForm($builder, []);
        
        //====================================================================//
        // Import Widget Option Form Fields
//        $this->Factory
//                ->populateWidgetForm($FormBuilder, $Fields, True);

                
        return $builder->getForm();
        
    }
    
}
