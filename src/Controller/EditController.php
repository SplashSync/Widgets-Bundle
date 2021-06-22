<?php

/*
 *  Copyright (C) 2021 BadPixxel <www.badpixxel.com>
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Splash\Widgets\Controller;

use Exception;
use Splash\Widgets\Entity\Widget;
use Splash\Widgets\Form\WidgetDatesType;
use Splash\Widgets\Form\WidgetOptionsType;
use Splash\Widgets\Services\FactoryService;
use Splash\Widgets\Services\ManagerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Manage Widget Parameters Edition
 */
class EditController extends AbstractController
{
    /**
     * Class Initialization
     *
     * @param string $service
     *
     * @return bool
     */
    public function initialize(string $service) : bool
    {
        return !empty($service);
    }

    //==============================================================================
    // AJAX MODALS
    //==============================================================================

    /**
     * Displays a Modal with Form to edit a Widget.
     *
     * @param Request        $request
     * @param ManagerService $manager
     * @param FactoryService $factory
     * @param string         $service
     * @param string         $type
     *
     * @throws Exception
     *
     * @return Response
     */
    public function modalAction(
        Request $request,
        ManagerService $manager,
        FactoryService $factory,
        string $service,
        string $type
    ) : Response {
        //==============================================================================
        // Init & Safety Check
        if (false == $this->initialize($service)) {
            return new Response("Error... ", 400);
        }
        //==============================================================================
        // Import Form Data & Prepare Data for Form Display
        $params = $this->prepare($request, $manager, $factory, $service, $type);
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
     * @param Request        $request
     * @param ManagerService $manager
     * @param FactoryService $factory
     * @param string         $service
     * @param string         $type
     *
     * @throws Exception
     *
     * @return Response
     */
    public function panelAction(
        Request $request,
        ManagerService $manager,
        FactoryService $factory,
        string $service,
        string $type
    ) : Response {
        //==============================================================================
        // Init & Safety Check
        if (false == $this->initialize($service)) {
            return new Response("Error... ", 400);
        }
        //==============================================================================
        // Import Form Data & Prepare Data for Form Display
        $params = $this->prepare($request, $manager, $factory, $service, $type);
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
     * @param Request        $request
     * @param ManagerService $manager
     * @param FactoryService $factory
     * @param string         $service
     * @param string         $type
     * @param null|Widget    $widget
     *
     * @throws Exception
     *
     * @return array
     */
    protected function prepare(
        Request $request,
        ManagerService $manager,
        FactoryService $factory,
        string $service,
        string $type,
        Widget $widget = null
    ) : array {
        //==============================================================================
        // Read Current Widget Options
        $options = $manager->getWidgetOptions($service, $type);
        //==============================================================================
        // Read Current Widget Parameters
        $parameters = $manager->getWidgetParameters($service, $type);
        //==============================================================================
        // Create Widget Object for Form
        if (null == $widget) {
            $widget = $factory
                ->create()
                ->setOptions($options)
                ->setParameters($parameters)
                ->getWidget();
        }
        //==============================================================================
        // Ajax => Setup Action for Ajax Submit
        $action = $request->isXmlHttpRequest()
                ? $this->generateUrl('splash_widgets_edit_widget', array('service' => $service, "type" => $type))
                : null;
        //==============================================================================
        // Create Edit Form
        $editForm = $this->createEditForm($manager, $widget, $service, $type, $action);
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
     * @param ManagerService $manager
     * @param Widget         $widget  The entity
     * @param string         $service
     * @param string         $type
     * @param null|string    $action
     *
     * @throws Exception
     *
     * @return FormInterface
     */
    private function createEditForm(
        ManagerService $manager,
        Widget $widget,
        string $service,
        string $type,
        ?string $action
    ) : FormInterface {
        //====================================================================//
        // Create Form Builder
        /** @var FormFactory $formFactory */
        $formFactory = $this->get('form.factory');
        $builder = $formFactory->createNamedBuilder(
            'splash_widgets_settings_form',
            FormType::class,
            $widget
        );
        if ($action) {
            $builder->setAction($action);
        }

        //====================================================================//
        // Populate Widget Rendering Option Form Tab
        $builder->add('options', WidgetOptionsType::class, array(
            'label' => false,
        ));

        //====================================================================//
        // Populate Widget Rendering Option Form Tab
        $paramForm = $builder->add('parameters', WidgetDatesType::class, array(
            'label' => false,
        ));

        //====================================================================//
        // Import Widget Option Form Fields
        $manager->populateWidgetForm($paramForm, $service, $type);

        return $builder->getForm();
    }
}
