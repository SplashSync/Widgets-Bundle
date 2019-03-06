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

use Splash\Widgets\Entity\WidgetCollection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Manage Widgets Collections
 */
class CollectionController extends Controller
{
    /**
     * Widget Collection
     *
     * @var WidgetCollection
     */
    private $collection;

    /**
     * Class Initialisation
     *
     * @param int $collectionId Widget Collection Id
     *
     * @return bool
     */
    public function initialize(int $collectionId = null) : bool
    {
        //==============================================================================
        // Load Collection
        $collection = $this->get("doctrine")
            ->getManager()
            ->getRepository("SplashWidgetsBundle:WidgetCollection")
            ->find($collectionId);
        //==============================================================================
        // Store Collection if Found
        if ($collection instanceof WidgetCollection) {
            $this->collection = $collection;

            return true;
        }

        return false;
    }

    /**
     * Render Widget Collection
     *
     * @param int $collectionId Widget Collection Id
     *
     * @return Response
     */
    public function viewAction(int $collectionId) : Response
    {
        //==============================================================================
        // Init & Safety Check
        if (!$this->initialize($collectionId)) {
            return new Response("Splash Widgets : Init Failed", 500);
        }

        foreach ($this->collection->getWidgets() as &$widget) {
            $widget->mergeOptions(array(
                "Editable" => true,
                "EditMode" => false,
            ));
        }
        //==============================================================================
        // Render Response
        return $this->render('SplashWidgetsBundle:View:collection.html.twig', array(
            "Collection" => $this->collection,
            "Edit" => false,
        ));
    }

    /**
     * Render Widget Collection
     *
     * @param int $collectionId Widget Collection Id
     *
     * @return Response
     */
    public function editAction(int $collectionId) : Response
    {
        //==============================================================================
        // Init & Safety Check
        if (!$this->initialize($collectionId)) {
            return new Response("Splash Widgets : Init Failed", 500);
        }

        foreach ($this->collection->getWidgets() as &$widget) {
            $widget->mergeOptions(array(
                "Editable" => true,
                "EditMode" => true,
            ));
        }

        //==============================================================================
        // Render Response
        return $this->render('SplashWidgetsBundle:View:collection.html.twig', array(
            "Collection" => $this->collection,
            "Edit" => true,
        ));
    }

    /**
     * Update Collection Widget Ordering from Ajax Request
     *
     * @param int    $collectionId
     * @param string $ordering
     *
     * @return Response
     */
    public function reorderAction(int $collectionId, string $ordering) : Response
    {
        //==============================================================================
        // Init & Safety Check
        if (!$this->initialize($collectionId)) {
            return new Response("Splash Widgets : Init Failed", 500);
        }
        //==============================================================================
        // Decode Json Value
        $orderArray = json_decode($ordering);
        //==============================================================================
        // Apply
        if (!$this->collection->reorder($orderArray)) {
            return new Response("Widget Collection ReOrder Failled", 400);
        }
        //==============================================================================
        // Save Changes
        $this->getDoctrine()->getManager()->Flush();

        return new Response("Widget Collection ReOrder Done", 200);
    }

    /**
     * Update Collection Dates Preset from Ajax Request
     *
     * @param int    $collectionId
     * @param string $preset
     *
     * @return Response
     */
    public function presetAction(int $collectionId, string $preset = "M") : Response
    {
        //==============================================================================
        // Init & Safety Check
        if (!$this->initialize($collectionId)) {
            return new Response("Splash Widgets : Init Failed", 500);
        }

        //==============================================================================
        // Update CVollection Itself
        $this->collection->setPreset($preset);
        foreach ($this->collection->getWidgets() as $widget) {
            if (!$widget->isPreset($preset)) {
                continue;
            }

            $this
                ->get("Splash.Widgets.Manager")
                ->setWidgetParameter(
                    $this->collection->getService(),
                    $widget->getId()."@".$this->collection->getid(),
                    "DatePreset",
                    $preset
                )
                    ;
        }
        //==============================================================================
        // Save Changes
        $this->getDoctrine()->getManager()->Flush();

        return new Response("Widget Collection Dates Preset Updated", 200);
    }

    /**
     * Add Widget to Collection from Ajax Request
     *
     * @param int    $collectionId
     * @param string $service
     * @param string $type
     *
     * @return Response
     */
    public function addAction(int $collectionId, string $service, string $type) : Response
    {
        //==============================================================================
        // Init & Safety Check
        if (!$this->initialize($collectionId)) {
            return new Response("Splash Widgets : Init Failed", 500);
        }
        //==============================================================================
        // Load Widget
        $widget = $this->get("Splash.Widgets.Manager")->getWidget($service, $type);
        if (is_null($widget)) {
            return new Response("Widget NOT Added to Collection", 400);
        }
        //==============================================================================
        // Add Widget To Collection
        $this->collection->addWidget($widget);
        //==============================================================================
        // Save Changes
        $this->getDoctrine()->getManager()->Flush();

        return new Response("Widget Added to Collection : ".$this->collection->getName(), 200);
    }

    /**
     * Remove Widget from Collection from Ajax Request
     *
     * @param string $service
     * @param string $type
     *
     * @return Response
     */
    public function removeAction(string $service, string $type) : Response
    {
        //==============================================================================
        // Init & Safety Check
        $manager = $this->has($service)
                ? $this->get($service)
                : $this->get("Splash.Widgets.Collection");
        if (empty($manager) || !method_exists($manager, "getDefinition")) {
            return new Response("Splash Widgets : Init Failed", 500);
        }
        //==============================================================================
        // Load Widget from Collection
        $widget = $manager->getDefinition($type);
        if (is_null($widget)) {
            return new Response("Widget NOT Removed to Collection", 400);
        }
        //==============================================================================
        // Add Widget To Collection
        $widget->getParent()->removeWidget($widget);
        $this->getDoctrine()->getManager()->Remove($widget);
        //==============================================================================
        // Save Changes
        $this->getDoctrine()->getManager()->Flush();

        return new Response("Widget Removed to Collection : ".$widget->getParent()->getName(), 200);
    }
}
