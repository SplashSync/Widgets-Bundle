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
use Splash\Widgets\Entity\WidgetCollection;
use Splash\Widgets\Services\ManagerService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Widgets Bundle Demonstration Pages Controller
 */
class DemoController extends EditController
{
    /**
     * Widget Manager Service
     *
     * @var ManagerService
     */
    private $manager;

    /**
     * Class Initialization
     *
     * @param string $service
     *
     * @return bool
     */
    public function initialize(string $service = null) : bool
    {
        parent::initialize((string) $service);
        //====================================================================//
        // Get Widget Manager Service
        $this->manager = $this->get("splash.widgets.manager");

        return true;
    }

    /**
     * @return Response
     */
    public function indexAction() : Response
    {
        //==============================================================================
        // Init & Safety Check
        $this->initialize();
        //==============================================================================
        // Load Demo Widgets from Channel List
        $widgets = $this->manager->getList(ManagerService::DEMO_WIDGETS);
        //==============================================================================
        // Init Demo Widgets Collection
        $demoCollection = $this->getDemoCollection();

        return $this->render('@SplashWidgets/Demo/index.html.twig', array(
            'Widgets' => $widgets,
            'Collection' => $demoCollection,
        ));
    }

    /**
     * @return Response
     */
    public function listAction() : Response
    {
        //==============================================================================
        // Init & Safety Check
        $this->initialize();
        //==============================================================================
        // Load Demo Widgets from Channel List
        $widgets = $this->manager->getList(ManagerService::DEMO_WIDGETS);

        return $this->render('@SplashWidgets/Demo/List/index.html.twig', array(
            'Widgets' => $widgets,
        ));
    }

    /**
     * @return Response
     */
    public function forcedAction() : Response
    {
        //==============================================================================
        // Init & Safety Check
        $this->initialize();

        return $this->render('@SplashWidgets/Demo/Single/forced.html.twig', array(
            'Widgets' => $this->manager->getList(ManagerService::DEMO_WIDGETS),
        ));
    }

    /**
     * @return Response
     */
    public function delayedAction() : Response
    {
        //==============================================================================
        // Init & Safety Check
        $this->initialize();

        return $this->render('@SplashWidgets/Demo/Single/delayed.html.twig', array(
            'Widgets' => $this->manager->getList(ManagerService::DEMO_WIDGETS),
        ));
    }

    /**
     * @param Request $request
     * @param string  $widgetType
     *
     * @return Response
     */
    public function editAction(Request $request, string $widgetType = "Text") : Response
    {
        //==============================================================================
        // Init & Safety Check
        $this->initialize();

        //==============================================================================
        // Load Demo Widget from Demo Factory
        $widget = $this->manager->getWidget("splash.widgets.demo.factory", $widgetType);
        if (!$widget) {
            return $this->redirectToRoute("splash_widgets_demo_list");
        }
        $widget->setWidth(Widget::$widthXl);

        //==============================================================================
        // Import Form Data & Prepare Data for Form Display
        $params = $this->prepare($request, "splash.widgets.demo.factory", $widgetType, $widget);

        return $this->render('@SplashWidgets/Demo/Single/edit.html.twig', $params);
    }

    /**
     * @return Response
     */
    public function collectionAction() : Response
    {
        //==============================================================================
        // Init & Safety Check
        $this->Initialize();
        //==============================================================================
        // Load Demo Widgets from Channel List
        $widgets = $this->manager->getList(ManagerService::DEMO_WIDGETS);
        //==============================================================================
        // Init Demo Widgets Collection
        $demoCollection = $this->getDemoCollection();

        return $this->render('@SplashWidgets/Demo/Collection/index.html.twig', array(
            'Widgets' => $widgets,
            'Collection' => $demoCollection,
            'Edit' => false,
        ));
    }

    /**
     * @return Response
     */
    public function collectionEditAction() : Response
    {
        //==============================================================================
        // Init & Safety Check
        $this->Initialize();
        //==============================================================================
        // Load Demo Widgets from Channel List
        $widgets = $this->manager->getList(ManagerService::DEMO_WIDGETS);
        //==============================================================================
        // Init Demo Widgets Collection
        $demoCollection = $this->getDemoCollection();

        return $this->render('@SplashWidgets/Demo/Collection/index.html.twig', array(
            'Widgets' => $widgets,
            'Collection' => $demoCollection,
            'Edit' => true,
        ));
    }

    /**
     * @return mixed
     */
    private function getDemoCollection()
    {
        //==============================================================================
        // Load Collection
        $demoCollection = $this->get("doctrine")
            ->getManager()
            ->getRepository(WidgetCollection::class)
            ->findOneBy(array("type" => "demo-collection"));

        //==============================================================================
        // Create Demo Collection
        if (!$demoCollection) {
            $demoCollection = new WidgetCollection();
            $demoCollection
                ->setName("Bundle Demonstration")
                ->setType("demo-collection");

            //==============================================================================
            // Load Demo Widgets from Channel List
            $widgets = $this->manager->getList(ManagerService::DEMO_WIDGETS);

            foreach ($widgets as $widget) {
                $demoCollection->addWidget($widget);
            }

            $entityManager = $this->get("doctrine")->getManager();
            $entityManager->persist($demoCollection);
            $entityManager->flush();
        }

        return $demoCollection;
    }
}
