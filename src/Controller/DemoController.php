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
use Splash\Widgets\Entity\WidgetCollection;
use Splash\Widgets\Services\FactoryService;
use Splash\Widgets\Services\ManagerService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Widgets Bundle Demonstration Pages Controller
 */
class DemoController extends EditController
{
    /**
     * @param ManagerService $manager
     *
     * @throws Exception
     *
     * @return Response
     */
    public function indexAction(ManagerService $manager) : Response
    {
        //==============================================================================
        // Load Demo Widgets from Channel List
        $widgets = $manager->getList(ManagerService::DEMO_WIDGETS);
        //==============================================================================
        // Init Demo Widgets Collection
        $demoCollection = $this->getDemoCollection($manager);

        return $this->render('@SplashWidgets/Demo/index.html.twig', array(
            'Widgets' => $widgets,
            'Collection' => $demoCollection,
        ));
    }

    /**
     * @param ManagerService $manager
     *
     * @throws Exception
     *
     * @return Response
     */
    public function listAction(ManagerService $manager) : Response
    {
        //==============================================================================
        // Load Demo Widgets from Channel List
        $widgets = $manager->getList(ManagerService::DEMO_WIDGETS);

        return $this->render('@SplashWidgets/Demo/List/index.html.twig', array(
            'Widgets' => $widgets,
        ));
    }

    /**
     * @param ManagerService $manager
     *
     * @throws Exception
     *
     * @return Response
     */
    public function forcedAction(ManagerService $manager) : Response
    {
        return $this->render('@SplashWidgets/Demo/Single/forced.html.twig', array(
            'Widgets' => $manager->getList(ManagerService::DEMO_WIDGETS),
        ));
    }

    /**
     * @param ManagerService $manager
     *
     * @throws Exception
     *
     * @return Response
     */
    public function delayedAction(ManagerService $manager) : Response
    {
        return $this->render('@SplashWidgets/Demo/Single/delayed.html.twig', array(
            'Widgets' => $manager->getList(ManagerService::DEMO_WIDGETS),
        ));
    }

    /**
     * @param Request        $request
     * @param ManagerService $manager
     * @param FactoryService $factory
     * @param string         $widgetType
     *
     * @throws Exception
     *
     * @return Response
     */
    public function editAction(
        Request $request,
        ManagerService $manager,
        FactoryService $factory,
        string $widgetType = "Text"
    ) : Response {
        //==============================================================================
        // Load Demo Widget from Demo Factory
        $widget = $manager->getWidget("splash.widgets.demo.factory", $widgetType);
        if (!$widget) {
            return $this->redirectToRoute("splash_widgets_demo_list");
        }
        $widget->setWidth(Widget::$widthXl);

        //==============================================================================
        // Import Form Data & Prepare Data for Form Display
        $params = $this->prepare($request, $manager, $factory, "splash.widgets.demo.factory", $widgetType, $widget);

        return $this->render('@SplashWidgets/Demo/Single/edit.html.twig', $params);
    }

    /**
     * @param ManagerService $manager
     *
     * @throws Exception
     *
     * @return Response
     */
    public function collectionAction(ManagerService $manager) : Response
    {
        //==============================================================================
        // Load Demo Widgets from Channel List
        $widgets = $manager->getList(ManagerService::DEMO_WIDGETS);
        //==============================================================================
        // Init Demo Widgets Collection
        $demoCollection = $this->getDemoCollection($manager);

        return $this->render('@SplashWidgets/Demo/Collection/index.html.twig', array(
            'Widgets' => $widgets,
            'Collection' => $demoCollection,
            'Edit' => false,
        ));
    }

    /**
     * @param ManagerService $manager
     *
     * @throws Exception
     *
     * @return Response
     */
    public function collectionEditAction(ManagerService $manager) : Response
    {
        //==============================================================================
        // Load Demo Widgets from Channel List
        $widgets = $manager->getList(ManagerService::DEMO_WIDGETS);
        //==============================================================================
        // Init Demo Widgets Collection
        $demoCollection = $this->getDemoCollection($manager);

        return $this->render('@SplashWidgets/Demo/Collection/index.html.twig', array(
            'Widgets' => $widgets,
            'Collection' => $demoCollection,
            'Edit' => true,
        ));
    }

    /**
     * @param ManagerService $manager
     *
     * @throws Exception
     *
     * @return WidgetCollection
     */
    private function getDemoCollection(ManagerService $manager): WidgetCollection
    {
        $entityManager = $this->getDoctrine()->getManager();
        //==============================================================================
        // Load Collection
        /** @var null|WidgetCollection $demoCollection */
        $demoCollection = $entityManager
            ->getRepository(WidgetCollection::class)
            ->findOneBy(array("type" => "demo-collection"))
        ;

        if (!$demoCollection) {
            //==============================================================================
            // Create Demo Collection
            $demoCollection = new WidgetCollection();
            $demoCollection
                ->setName("Bundle Demonstration")
                ->setType("demo-collection")
            ;
            //==============================================================================
            // Load Demo Widgets from Channel List
            $widgets = $manager->getList(ManagerService::DEMO_WIDGETS);
            foreach ($widgets as $widget) {
                $demoCollection->addWidget($widget);
            }
            //==============================================================================
            // Save Demo Collection
            $entityManager->persist($demoCollection);
            $entityManager->flush();
        }

        return $demoCollection;
    }
}
