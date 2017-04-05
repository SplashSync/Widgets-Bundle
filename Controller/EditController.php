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
     * Class Initialization
     * 
     * @return bool 
     */    
    public function initialize() {
        
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
        if (!$this->initialize($Service)) {
            return new Response("Error... ", 400);
        }   
        //==============================================================================
        // Import Form Data & Prepare Data for Form Display   
        $Params = $this->prepare($request, $Service, $Type);
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
        $Manager = $this->get("Splash.Widgets.Manager");
        
        //==============================================================================
        // Read Current Widget Options 
        $Options    =   $Manager->getWidgetOptions($Service, $Type);
        //==============================================================================
        // Read Current Widget Parameters 
        $Parameters =   $Manager->getWidgetParameters($Service, $Type);
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
        $this->EditForm = $this->createEditForm($Widget, $Service, $Type, $Action);
        //==============================================================================
        // Handle User Posted Data
        $this->EditForm->handleRequest($request);
        //==============================================================================
        // Verify Data is Valid
        if ( $this->EditForm->isSubmitted() && $this->EditForm->isValid() ) {
            //==============================================================================
            // Save Changes
            $Manager->setWidgetOptions($Service, $Type, $Widget->getOptions());
            $Manager->setWidgetParameters($Service, $Type, $Widget->getParameters());
        }
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
    private function createEditForm(Widget $Widget, $Service, $Type, $Action = Null)
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
        
        $WidgetTab = $builder->create('parameters', \Mopa\Bundle\BootstrapBundle\Form\Type\TabType::class, array(
            'label'                 => 'widget.params',
            'translation_domain'    => "SplashWidgetsBundle",
            'icon'                  => ' fa fa-cogs',
            'inherit_data'          => true,
        ));
        
        //====================================================================//
        // Import Widget Option Form Fields
        $this->get("Splash.Widgets.Manager")->populateWidgetForm($WidgetTab, $Service, $Type);
        if (count($WidgetTab->all())) {
            $builder->add($WidgetTab);
        }
                
        return $builder->getForm();
        
    }
    
}
