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

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Build & Display Lists of Avaimlable Widgets
 */
class ListController extends Controller
{
    /**
     * Class Initialization
     * Used in Secured Apps to Ensure User is Logged
     *
     * @return bool
     */
    public function initialize()
    {
        return true;
    }

    //==============================================================================
    // PANEL
    //==============================================================================

    /**
     * Render List of Collection Available Widgets
     *
     * @param int    $collectionId Widgets Collection Identifier
     * @param string $channel      Widgets Listening Channel Name
     *
     * @return Response
     */
    public function panelAction(int $collectionId, string $channel) : Response
    {
        //==============================================================================
        // Init & Safety Check
        if (!$this->initialize()) {
            return new Response("You are not logged... ", 400);
        }
        //==============================================================================
        // Read List of Available Widgets & Prepare Response Array
        $params = $this->prepare($collectionId, $channel);
        //==============================================================================
        // Render Panel List
        return $this->render('SplashWidgetsBundle:List:panel.html.twig', $params);
    }

    //==============================================================================
    // AJAX MODALS
    //==============================================================================

    /**
     * Render Modal List of Collection available Widgets
     *
     * @param int    $collectionId Widgets Collection Identifier
     * @param string $channel      Widgets Listening Channel Name
     *
     * @return Response
     */
    public function modalAction(int $collectionId, string $channel) : Response
    {
        //==============================================================================
        // Init & Safety Check
        if (!$this->initialize()) {
            return new Response("You are not logged... ", 400);
        }
        //==============================================================================
        // Import Form Data & Prepare Data for Form Display
        $params = $this->prepare($collectionId, $channel);
        //==============================================================================
        //Render Modal List
        return $this->render('SplashWidgetsBundle:List:modal.html.twig', $params);
    }

    /**
     * Read List of Available Widgets & Prepare Response Array
     *
     * @param int    $collectionId Widgets Collection Identifier
     * @param string $channel      Widgets Listening Channel Name
     *
     * @return array
     */
    private function prepare(int $collectionId, string $channel) : array
    {
        //==============================================================================
        // Get List of Widgets
        $widgets = $this->get("splash.widgets.manager")->getList("splash.widgets.list.".$channel);

        //==============================================================================
        // Prepare Tabs List
        $tabs = array();
        foreach ($widgets as $key => $widget) {
            $tabId = md5(base64_encode($widget->getOrigin()));
            //==============================================================================
            // Create Tab
            if (!isset($tabs[$tabId])) {
                $tabs[$tabId] = array(
                    "label" => $widget->getOrigin(),
                    "id" => $tabId,
                    "widgets" => array(),
                );
            }
            //==============================================================================
            // Add To Tab
            $tabs[$tabId]["widgets"][] = $key;
        }

        //==============================================================================
        // Prepare Rendering Parameters
        return array(
            'CollectionId' => $collectionId,
            'Widgets' => $widgets,
            'Tabs' => $tabs,
        );
    }
}
