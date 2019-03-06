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

use Splash\Widgets\Entity\WidgetCache;
use Splash\Widgets\Models\WidgetBase        as Widget;
use Splash\Widgets\Services\ManagerService;
use Splash\Widgets\Tests\Blocks\Test;
use Splash\Widgets\Tests\Services\SamplesFactoryService as SamplesFactory;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Test of Widgets manager Service
 */
class A003WidgetManagerServiceTest extends KernelTestCase
{
    use \Splash\Widgets\Tests\Traits\ContainerAwareTrait;

    /**
     * {@inheritDoc}
     */
    protected function setUp() : void
    {
        self::bootKernel();
    }

    /**
     * Check Manager Class
     */
    public function testManagerClass() : void
    {
        //====================================================================//
        // Link to Widget Manager Service
        $manager = $this->getContainer()->get('Splash.Widgets.Manager');
        //====================================================================//
        // Check Class
        $this->assertInstanceOf(ManagerService::class, $manager);
    }

    /**
     * Check Manager Listing Functions
     */
    public function testManagerListing() : void
    {
        //====================================================================//
        // Get Wrong List
        $wrongList = $this->getManager()->getList(ManagerService::TEST_WIDGETS.".wrong");

        //====================================================================//
        // Check List
        $this->assertIsArray($wrongList);
        $this->assertCount(0, $wrongList);

        //====================================================================//
        // Get Test List
        $testList = $this->getManager()->getList(ManagerService::TEST_WIDGETS);

        //====================================================================//
        // Check List
        $this->assertIsArray($testList);
        $this->assertCount(1, $testList);

        //====================================================================//
        // Get Demo List
        $demoList = $this->getManager()->getList(ManagerService::DEMO_WIDGETS);

        //====================================================================//
        // Check List
        $this->assertIsArray($demoList);
        $this->assertGreaterThan(1, count($demoList));
    }

    /**
     * Check Manager Connect Functions
     */
    public function testManagerConnect() : void
    {
        //====================================================================//
        // Get Test Factory Service
        $this->assertTrue(
            $this->getManager()->Connect(SamplesFactory::SERVICE)
        );

        //====================================================================//
        // Get Existant Factory Service
        $this->assertFalse(
            $this->getManager()->Connect(SamplesFactory::SERVICE."Unknown")
        );

        //====================================================================//
        // Get Existant Factory Service
        $this->expectException(\Exception::class);
        $this->getManager()->Connect("router");
    }

    /**
     * Check Manager Reading Widgets Contents
     */
    public function testManagerGetTestWidget() : void
    {
        //====================================================================//
        // Get Test Widget
        $testWidget = $this->getManager()->getWidget(SamplesFactory::SERVICE, "Test");

        //====================================================================//
        // Check Widget
        $this->assertInstanceOf(Widget::class, $testWidget);
        $this->assertEquals(Test::TYPE, $testWidget->getType());
        $this->assertGreaterThan(0, $testWidget->getBlocks()->count());
    }

    /**
     * Check Manager Widgets Cache Management
     */
    public function testManagerCache() : void
    {
        //====================================================================//
        // Link to Entity Manager Service
        $entityManager = $this->getEntityManager();

        //====================================================================//
        // Get Test Widget
        $testWidget = $this->getManager()->getWidget(SamplesFactory::SERVICE, "Test");
        $this->assertInstanceOf(Widget::class, $testWidget);

        //====================================================================//
        // Setup Two Widgets in Cache
        $testWidget->setParameters(array( ));
        $this->getManager()->setCacheContents($testWidget, SamplesFactory::SERVICE);
        $testWidget->setParameters(array( Test::TYPE => SamplesFactory::SERVICE));
        $this->getManager()->setCacheContents($testWidget, SamplesFactory::SERVICE);

        //====================================================================//
        // Load Both Widgets From Cache
        $cache1 = $this->getManager()->getCache($testWidget->getService(), $testWidget->getType(), $testWidget->getOptions(), array());
        $cache2 = $this->getManager()->getCache($testWidget->getService(), $testWidget->getType(), $testWidget->getOptions(), array( Test::TYPE => SamplesFactory::SERVICE));

        //====================================================================//
        // Check Widgets Cache
        $this->assertInstanceOf(WidgetCache::class, $cache1);
        $this->assertInstanceOf(WidgetCache::class, $cache2);
        $this->assertNotEquals($cache1->getId(), $cache2->getId());

        $this->assertEquals($testWidget->getService(), $cache1->getService());
        $this->assertEquals($testWidget->getType(), $cache1->getType());
        $this->assertEquals($testWidget->getOptions(), $cache1->getOptions());
        $this->assertEquals(SamplesFactory::SERVICE, $cache1->getContents());

        $this->assertEquals($testWidget->getService(), $cache2->getService());
        $this->assertEquals($testWidget->getType(), $cache2->getType());
        $this->assertEquals($testWidget->getOptions(), $cache2->getOptions());
        $this->assertEquals(SamplesFactory::SERVICE, $cache2->getContents());

        //====================================================================//
        // Set Cache1 as Expired
        $cache1->setExpireAt(new \DateTime("-10 minute"));
        $entityManager->flush();

        //====================================================================//
        // ReLoad Both Widgets From Cache
        $this->assertNull(
            $this->getManager()->getCache($testWidget->getService(), $testWidget->getType(), $testWidget->getOptions(), array())
        );
        $this->assertInstanceOf(
            WidgetCache::class,
            $this->getManager()->getCache($testWidget->getService(), $testWidget->getType(), $testWidget->getOptions(), array( Test::TYPE => SamplesFactory::SERVICE))
        );

        //====================================================================//
        // Clean Expired Widgets From Cache
        $this->getManager()->cleanCache();

        //====================================================================//
        // ReLoad Both Widgets From Cache
        $this->assertNull(
            $entityManager->getRepository("SplashWidgetsBundle:WidgetCache")->find($cache1->getId())
        );
        $this->assertInstanceOf(
            WidgetCache::class,
            $entityManager->getRepository("SplashWidgetsBundle:WidgetCache")->find($cache2->getId())
        );
    }
}
