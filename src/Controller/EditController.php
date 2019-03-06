<?php

/*
 *  This file is part of SplashSync Project.
 *
 *  Copyright (C) 2015-2019 Splash Sync  <www.splashsync.com>
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Splash\Widgets\Controller;

use Splash\Widgets\Entity\Widget;
use Splash\Widgets\Form\WidgetDatesType;
use Splash\Widgets\Form\WidgetOptionsType;
use Splash\Widgets\Services\FactoryService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Manage Widget Parameters Edition
 */
class EditController extends Controller
{
    /**
     * WidgetFactory Service
     *
     * @var FactoryService
     */
    private $factory;

    /**
     * Class Initialization
     *
     * @param string $service
     *
     * @return bool
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function initialize(string $service) : bool
    {
        $this->factory = $this->get("Splash.Widgets.Factory");

        return true;
    }

    //==============================================================================
    // AJAX MODALS
    //==============================================================================

    /**
     * Displays a Modal with Form to edit a Widget.
     *
     * @param Request $request
     * @param string  $service
     * @param string  $type
     *
     * @return Response
     */
    public function modalAction(Request $request, string $service, string $type) : Response
    {
        //==============================================================================
        // Init & Safety Check
        if (!$this->initialize($service)) {
            return new Response("Error... ", 400);
        }
        //==============================================================================
        // Import Form Data & Prepare Data for Form Display
        $params = $this->prepare($request, $service, $type);
        //==============================================================================
        // Render Widget Edit Modal
        return $this->render('SplashWidgetsBundle:Edit:modal.html.twig', $params);
    }

    //==============================================================================
    // NON-AJAX ACTIONS
    //==============================================================================

    /**
     * Displays a Panel with Form to edit a Widget.
     *
     * @param Request $request
     * @param string  $service
     * @param string  $type
     *
     * @return Response
     */
    public function panelAction(Request $request, string $service, string $type) : Response
    {
        //==============================================================================
        // Init & Safety Check
        if (true != $this->initialize($service)) {
            return new Response("Error... ", 400);
        }
        //==============================================================================
        // Import Form Data & Prepare Data for Form Display
        $params = $this->prepare($request, $service, $type);
        //==============================================================================
        // Render Widget Edit Well
        return $this->render('SplashWidgetsBundle:Edit:panel.html.twig', $params);
    }

    //==============================================================================
    // LOW LEVEL FUNCTIONS
    //==============================================================================

    /**
     * Prepare Data to Display Edit form on a Widget
     *
     * @param Request $request
     * @param string  $service
     * @param string  $type
     *
     * @return array
     */
    private function prepare(Request $request, string $service, string $type) : array
    {
        //==============================================================================
        // Connect to Widgets Manager
        $manager = $this->get("Splash.Widgets.Manager");
        //==============================================================================
        // Read Current Widget Options
        $options = $manager->getWidgetOptions($service, $type);
        //==============================================================================
        // Read Current Widget Parameters
        $parameters = $manager->getWidgetParameters($service, $type);
        //==============================================================================
        // Create Widget Object for Form
        $widget = $this->factory
            ->create()
            ->setOptions($options)
            ->setParameters($parameters)
            ->getWidget();
        //==============================================================================
        // Ajax => Setup Action for Ajax Submit
        $action = $request->isXmlHttpRequest()
                ? $this->generateUrl('splash_widgets_edit_widget', array('service' => $service, "type" => $type))
                : null;
        //==============================================================================
        // Create Edit Form
        $editForm = $this->createEditForm($widget, $service, $type, $action);
        //==============================================================================
        // Handle User Posted Data
        $editForm->handleRequest($request);
        //==============================================================================
        // Verify Data is Valid
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            //==============================================================================
            // Save Changes
            $manager->setWidgetOptions($service, $type, $widget->getOptions());
            $manager->setWidgetParameters($service, $type, $widget->getParameters());
        }
        //==============================================================================
        // Prepare Template Parameters
        return array(
            'Widget' => $widget,
            'Form' => $editForm->createView(),
        );
    }

    /**
     * Creates a form to edit a Remote Widget/Item Parameters.
     *
     * @param Widget      $widget  The entity
     * @param string      $service
     * @param string      $type
     * @param null|string $action
     *
     * @return FormInterface
     */
    private function createEditForm(Widget $widget, string $service, string $type, ?string $action) : FormInterface
    {
        //====================================================================//
        // Create Form Builder
        $builder = $this->get('form.factory')
            ->createNamedBuilder('splash_widgets_settings_form', FormType::class, $widget);
        if ($action) {
            $builder->setAction($action);
        }

        //====================================================================//
        // Populate Widget Rendering Option Form Tab
        $widgetOptionsForm = new WidgetOptionsType();
        $widgetOptionsForm->buildForm($builder, array());

        //====================================================================//
        // Populate Widget Rendering Option Form Tab
        $widgetDatesForm = new WidgetDatesType();
        $widgetDatesForm->buildForm($builder, array());

        $widgetTab = $builder->create('parameters', \Mopa\Bundle\BootstrapBundle\Form\Type\TabType::class, array(
            'label' => 'widget.params',
            'translation_domain' => "SplashWidgetsBundle",
            'icon' => ' fa fa-cogs',
            'inherit_data' => true,
            'attr' => array(
                'class' => 'well-sm',
            ),
        ));

        //====================================================================//
        // Import Widget Option Form Fields
        $this->get("Splash.Widgets.Manager")->populateWidgetForm($widgetTab, $service, $type);
        if (count($widgetTab->all())) {
            $builder->add($widgetTab);
        }

        return $builder->getForm();
    }
}
