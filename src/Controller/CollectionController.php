<?php

/*
 *  This file is part of SplashSync Project.
 *
 *  Copyright (C) 2015-2020 Splash Sync  <www.splashsync.com>
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
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
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
            ->getRepository(WidgetCollection::class)
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
     * @param Request $request
     * @param int     $collectionId
     *
     * @return Response
     */
    public function reorderAction(Request $request, int $collectionId) : Response
    {
        //==============================================================================
        // Init & Safety Check
        if (!$this->initialize($collectionId)) {
            return new Response("Splash Widgets : Init Failed", 500);
        }
        //==============================================================================
        // Retreive New Order from Post
        $orderArray = $request->request->get("ordering");
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
     * @param Request $request
     * @param int     $collectionId
     * @param string  $preset
     *
     * @return Response
     */
    public function presetAction(Request $request, int $collectionId, string $preset = "M") : Response
    {
        //==============================================================================
        // Init & Safety Check
        if (!$this->initialize($collectionId)) {
            return $this->redirectToReferer($request);
        }

        //==============================================================================
        // Update CVollection Itself
        $this->collection->setPreset($preset);
        foreach ($this->collection->getWidgets() as $widget) {
            if (!$widget->isPreset($preset)) {
                continue;
            }

            $this
                ->get("splash.widgets.manager")
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

        return $this->redirectToReferer($request);
    }

    /**
     * Add Widget to Collection from Ajax Request
     *
     * @param Request $request
     * @param int     $collectionId
     * @param string  $service
     * @param string  $type
     *
     * @return Response
     */
    public function addAction(Request $request, int $collectionId, string $service, string $type) : Response
    {
        //==============================================================================
        // Init & Safety Check
        if (!$this->initialize($collectionId)) {
            return $this->redirectToReferer($request);
        }
        //==============================================================================
        // Load Widget
        $widget = $this->get("splash.widgets.manager")->getWidget($service, $type);
        if (is_null($widget)) {
            return $this->redirectToReferer($request);
        }
        //==============================================================================
        // Add Widget To Collection
        $this->collection->addWidget($widget);
        //==============================================================================
        // Save Changes
        $this->getDoctrine()->getManager()->Flush();

        return $this->redirectToReferer($request);
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
                : $this->get("splash.widgets.collection");
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

    //==============================================================================
    // OTHER ACTIONS
    //==============================================================================

    /**
     * Redirect to Action Referer.
     *
     * @param Request $request
     *
     * @return RedirectResponse
     */
    protected function redirectToReferer(Request $request): RedirectResponse
    {
        //====================================================================//
        // Build Redirect Response
        /** @var string $referer */
        $referer = $request->headers->get('referer');

        return new RedirectResponse($referer);
    }
}
