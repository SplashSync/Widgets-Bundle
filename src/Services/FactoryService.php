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

namespace Splash\Widgets\Services;

use ArrayObject;
use Splash\Widgets\Entity\Widget;
use Splash\Widgets\Models\Blocks\BaseBlock;

/**
 * Widget Factory Service
 */
class FactoryService
{
    /**
     * @var Widget
     */
    private $widget;

    /**
     * @var BaseBlock
     */
    private $block;

    //====================================================================//
    //  CONSTRUCTOR
    //====================================================================//

    /**
     * Class Constructor
     */
    public function __construct()
    {
        $this->widget = new Widget();
    }

    //====================================================================//
    // *******************************************************************//
    //  Widget Getter & Setter Functions
    // *******************************************************************//
    //====================================================================//

    /**
     * Magic Widget Getter Function
     *
     * @param string $name Function Name
     * @param array  $args Function Arguments
     *
     * @return $this
     */
    public function __call(string $name, array $args) : self
    {
        if (method_exists($this->widget, $name)) {
            switch (count($args)) {
                case 0:
                    $this->widget->{$name}();

                    break;
                case 1:
                    $this->widget->{$name}($args[0]);

                    break;
                case 2:
                    $this->widget->{$name}($args[0], $args[1]);

                    break;
            }
        }

        return $this;
    }

    //====================================================================//
    // *******************************************************************//
    //  WIDGET PARSING FUNCTIONS
    // *******************************************************************//
    //====================================================================//

    /**
     * Create New Widget
     *
     * @param array $options
     * @param array $contents
     *
     * @return $this
     */
    public function create(array $options = null, array $contents = null) : self
    {
        //====================================================================//
        // Create an Empty Widget
        $this->widget = new Widget();
        //====================================================================//
        // Setup Widget Options
        if (!is_null($options)) {
            $this->widget->setOptions($options);
        }
        //====================================================================//
        // Setup Widget Contents
        if (!is_null($contents)) {
            $this->widget->setContents($contents);
            $this->addBlocks($contents["blocks"]);
        }

        return $this;
    }

    /**
     * Get Current Created Widget
     *
     * @return Widget
     */
    public function getWidget() : Widget
    {
        //==============================================================================
        //  If no Id Defined, Generate Unique Widget Id
//        if ( empty( $this->widget->getId() ) ) {
//            $this->widget->setIdentifier( md5( json_encode($this->widget) ) );
//        }
        //==============================================================================
        //  Return Current Widget Data
        return $this->widget;
    }

    //====================================================================//
    // *******************************************************************//
    //  BLOCKS PARSING FUNCTIONS
    // *******************************************************************//
    //====================================================================//

    /**
     * Add Multiple Blocks to a Widget
     *
     * @param null|array|ArrayObject $blocks
     *
     * @return $this
     */
    public function addBlocks($blocks) : self
    {
        //==============================================================================
        //  Safety Check
        if (!is_iterable($blocks)) {
            return $this;
        }
        //==============================================================================
        //  Walk on Given Blocks
        foreach ($blocks as $block) {
            //====================================================================//
            // Check Block Contents are Valid
            if (!self::isValidBlock($block)) {
                continue;
            }
            //====================================================================//
            // Check Block Contents are Valid
            if (!isset($block["options"]) || empty($block["options"])) {
                $block["options"] = null;
            }
            //====================================================================//
            // Add Block To Widget
            $this->addBlock($block["type"], $block["options"], $block["data"]);
        }

        return $this;
    }

    /**
     * Add New Block to Widget
     *
     * @param string $type
     * @param array  $options
     * @param array  $contents
     *
     * @return BaseBlock
     */
    public function addBlock(string $type, array $options = null, array $contents = null) : BaseBlock
    {
        //====================================================================//
        // Build Block Type ClassName
        $blockClassName = '\Splash\Widgets\Models\Blocks\\'.$type;
        //====================================================================//
        // Check if Requested Block Type Exists
        $this->block = class_exists($blockClassName)
                ? new $blockClassName()
                : new BaseBlock();
        //====================================================================//
        // Setup Parent Pointer
        $this->block->parent = $this;
        //====================================================================//
        // Setup Widget Options
        if (!is_null($options)) {
            $this->block->setOptions($options);
        }
        //====================================================================//
        // Setup Widget Contents
        if (!is_null($contents)) {
            $this->block->setContents($contents);
        }
        //====================================================================//
        // Add Block to Widget
        $this->widget->addBlock($this->block);

        return $this->block;
    }

    //====================================================================//
    // *******************************************************************//
    //  PREDEFINED BLOCKS GENERATION
    // *******************************************************************//
    //====================================================================//

    /**
     * Build a Generic  Error Widget
     *
     * @param string $service
     * @param string $widgetId
     * @param string $error
     *
     * @return Widget
     */
    public function buildErrorWidget(string $service, string $widgetId, string $error) : Widget
    {
        $this
            //==============================================================================
            // Create Widget
            ->create()
            ->setTitle("Error")
            ->setIcon("fa fa-exclamation-triangle text-danger")
            ->setService($service)
            ->setType($widgetId)
            ->end()
            //==============================================================================
            // Create Notifications Block
            ->addBlock("NotificationsBlock")
            ->setError($error)
            ->end()
        ;

        return $this->getWidget();
    }

    //====================================================================//
    //  PRIVATE FUNCTIONS
    //====================================================================//

    /**
     * Verify Block Contents are Valid
     *
     * @param array $block
     *
     * @return bool
     */
    private static function isValidBlock(array $block) : bool
    {
        if (!isset($block["type"]) || !isset($block["data"])) {
            return false;
        }
        if (empty($block["type"]) || empty($block["data"])) {
            return false;
        }
        if (isset($block["options"]) && !is_array($block["options"])) {
            return false;
        }

        return true;
    }
}
