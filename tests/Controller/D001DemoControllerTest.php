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

namespace Splash\Widgets\Tests\Controller;

use Splash\Widgets\Tests\Traits\UrlCheckerTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Verify Loading of All Demo Controller Routes
 */
class D001DemoControllerTest extends WebTestCase
{
    use UrlCheckerTrait;

    /**
     * @var array
     */
    const DEMO_ROUTES = array(
        "splash_widgets_demo_home",
        "splash_widgets_demo_single_blocks",
        "splash_widgets_demo_collection",
        "splash_widgets_demo_collection_edit",
        "splash_widgets_demo_list",
        "splash_widgets_demo_single_forced",
        "splash_widgets_demo_single_delayed",
        "splash_widgets_demo_single_edit",
        "splash_widgets_demo_blocks",
    );

    /**
     * {@inheritDoc}
     */
    protected function setUp() : void
    {
        $this->client = self::createClient();
    }

    /**
     * Check Manager Class
     *
     * @dataProvider demoRoutesProvider
     *
     * @param mixed $route
     */
    public function testDemoPages($route) : void
    {
        $this->assertRouteWorks($route);
    }

    /**
     * Demo Controller Routes Data Provider
     *
     * @return array
     */
    public function demoRoutesProvider() : array
    {
        $routes = array();

        foreach (self::DEMO_ROUTES as $route) {
            $routes[$route] = array($route);
        }

        return $routes;
    }
}
