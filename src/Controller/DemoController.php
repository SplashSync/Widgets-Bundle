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

use Splash\Widgets\Services\FactoryService;
use Splash\Widgets\Services\ManagerService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Widgets Bundle Demonstration Pages Controller
 */
class DemoController extends Controller
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
     * @return bool
     */
    public function initialize() : bool
    {
        //====================================================================//
        // Get WidgetFactory Service
        $this->factory = $this->get("Splash.Widgets.Factory");

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
        $widgets = $this->get("Splash.Widgets.Manager")->getList(ManagerService::DEMO_WIDGETS);
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
        $this->Initialize();
        //==============================================================================
        // Load Demo Widgets from Channel List
        $widgets = $this->get("Splash.Widgets.Manager")->getList(ManagerService::DEMO_WIDGETS);

        return $this->render('@SplashWidgets/Demo/List/index.html.twig', array(
            'Widgets' => $widgets,
        ));
    }

    /**
     * @return Response
     */
    public function forcedAction() : Response
    {
        return $this->render('@SplashWidgets/Demo/Single/forced.html.twig', array(
            'Widgets' => $this->get("Splash.Widgets.Manager")->getList(ManagerService::DEMO_WIDGETS),
        ));
    }

    /**
     * @return Response
     */
    public function delayedAction() : Response
    {
        return $this->render('@SplashWidgets/Demo/Single/delayed.html.twig', array(
            'Widgets' => $this->get("Splash.Widgets.Manager")->getList(ManagerService::DEMO_WIDGETS),
        ));
    }

    /**
     * @return Response
     */
    public function editAction() : Response
    {
        return $this->render('@SplashWidgets/Demo/Single/edit.html.twig', array());
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
        $widgets = $this->get("Splash.Widgets.Manager")->getList(ManagerService::DEMO_WIDGETS);
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
        $widgets = $this->get("Splash.Widgets.Manager")->getList(ManagerService::DEMO_WIDGETS);
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
            ->getRepository("SplashWidgetsBundle:WidgetCollection")
            ->findOneByType("demo-collection");

        //==============================================================================
        // Create Demo Collection
        if (!$demoCollection) {
            $demoCollection = new \Splash\Widgets\Entity\WidgetCollection();
            $demoCollection
                ->setName("Bundle Demonstration")
                ->setType("demo-collection");

            //==============================================================================
            // Load Demo Widgets from Channel List
            $widgets = $this->get("Splash.Widgets.Manager")->getList(ManagerService::DEMO_WIDGETS);

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
