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

use Splash\Widgets\Models\Blocks\BaseBlock;
use Splash\Widgets\Models\WidgetBase as Widget;
use Splash\Widgets\Services\FactoryService;
use Splash\Widgets\Services\ManagerService;
use Splash\Widgets\Tests\Blocks\Test;
use Splash\Widgets\Tests\Services\SamplesFactoryService as SamplesFactory;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Test of Splash Widget Factory Service
 */
class A002WidgetFactoryServiceTest extends KernelTestCase
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
     * Check Factory Class
     */
    public function testFactoryClass() : void
    {
        //====================================================================//
        // Check Class
        $this->assertInstanceOf(FactoryService::class, $this->getFactory());
    }

    /**
     * Check Factory Functions
     */
    public function testFactoryCreateProcess() : void
    {
        //====================================================================//
        // Create a New Widget
        $this->getFactory()->create();

        //====================================================================//
        // Check Widget
        $this->assertInstanceOf(Widget::class, $this->getFactory()->getWidget());
        $this->assertEquals(null, $this->getFactory()->getWidget()->getType());

        //====================================================================//
        // Setup Widget Options
        $this->getFactory()
            ->setService(SamplesFactory::SERVICE)
            ->setType(Test::TYPE)
            ->setTitle(Test::TITLE)
            ->setIcon(Test::ICON)
            ->setName(Test::TITLE)
            ->setDescription(Test::DESCRIPTION)
            ->setOrigin(SamplesFactory::ORIGIN)
            ;

        //====================================================================//
        // Check Widget
        $this->assertInstanceOf(Widget::class, $this->getFactory()->getWidget());
        $this->assertEquals(Test::TYPE, $this->getFactory()->getWidget()->getType());
        $this->assertEquals(SamplesFactory::SERVICE, $this->getFactory()->getWidget()->getService());
        $this->assertEquals(Test::TITLE, $this->getFactory()->getWidget()->getTitle());
        $this->assertEquals(Test::ICON, $this->getFactory()->getWidget()->getIcon());
        $this->assertEquals(Test::TITLE, $this->getFactory()->getWidget()->getName());
        $this->assertEquals(Test::DESCRIPTION, $this->getFactory()->getWidget()->getDescription());
    }

    /**
     * Check Factory Functions
     */
    public function testFactoryWidgetOptions() : void
    {
        //====================================================================//
        // Create a New Widget
        $this->getFactory()->Create();

        //====================================================================//
        // Check Widget
        $this->assertInstanceOf(Widget::class, $this->getFactory()->getWidget());
        $this->assertEquals(Widget::getDefaultOptions(), $this->getFactory()->getWidget()->getOptions());

        //====================================================================//
        // Setup Empty Options
        $this->getFactory()->setOptions(array());

        //====================================================================//
        // Check Widget
        $this->assertInstanceOf(Widget::class, $this->getFactory()->getWidget());
        $this->assertEquals(Widget::getDefaultOptions(), $this->getFactory()->getWidget()->getOptions());

        //====================================================================//
        // Setup Wrong Options
        $this->getFactory()->setOptions(array("ThisOptionDoNotExists" => "This Key is Uselless"));

        //====================================================================//
        // Check Widget
        $this->assertInstanceOf(Widget::class, $this->getFactory()->getWidget());
        $this->assertEquals(Widget::getDefaultOptions(), $this->getFactory()->getWidget()->getOptions());

        //====================================================================//
        // Setup All Options
        $this->getFactory()
            ->setWidth("col-test-12")
            ->setHeader(false)
            ->setFooter(false)
            ->mergeOptions(array(
                "Color" => "#666666",
                "DatePreset" => "D",
                "UseCache" => false,
                "CacheLifeTime" => 3,
                "Editable" => true,
                "EditMode" => true,
            ))
            ;

        //====================================================================//
        // Check Widget
        $this->assertInstanceOf(Widget::class, $this->getFactory()->getWidget());
        $this->assertEquals(array(
            'Width' => "col-test-12",
            'Color' => "#666666",
            'Header' => false,
            'Footer' => false,
            'Border' => true,
            'DatePreset' => "D",
            'UseCache' => false,
            'CacheLifeTime' => 3,
            'Editable' => true,
            'EditMode' => true,
            'Mode' => "bs4",
        ), $this->getFactory()->getWidget()->getOptions());
    }

    /**
     * Check Factory Functions
     *
     * @dataProvider blocksNamesProvider
     *
     * @param string $name
     */
    public function testFactoryWidgetBlocks(string $name) : void
    {
        //====================================================================//
        // Create a New Widget
        $this->getFactory()->Create();

        //====================================================================//
        // Add Block
        $block = $this->getFactory()->addBlock($name);

        //====================================================================//
        // Build Block Type ClassName
        $blockClassName = '\Splash\Widgets\Models\Blocks\\'.$name;

        //====================================================================//
        // Check Block
        $this->assertInstanceOf(BaseBlock::class, $block);
        $this->assertInstanceOf($blockClassName, $block);

        //====================================================================//
        // Check Widget
        $this->assertNotEmpty($this->getFactory()->getWidget()->getBlocks());
        $this->assertEquals(1, $this->getFactory()->getWidget()->getBlocks()->count());
        $this->assertInstanceOf($blockClassName, $this->getFactory()->getWidget()->getBlocks()->first());
    }

    /**
     * Tests Blocks Names Data Provider
     *
     * @return array
     */
    public function blocksNamesProvider() : array
    {
        $list = array();
        foreach (ManagerService::AVAILABLE_BLOCKS as $blockName) {
            $list[] = array($blockName);
        }

        return $list;
    }
}
