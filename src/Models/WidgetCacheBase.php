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

namespace Splash\Widgets\Models;

use Splash\Widgets\Entity\Widget;
use Splash\Widgets\Models\Traits\AccessTrait;
use Splash\Widgets\Models\Traits\CacheTrait;
use Splash\Widgets\Models\Traits\DefinitionTrait;
use Splash\Widgets\Models\Traits\OptionsTrait;
use Splash\Widgets\Models\Traits\ParametersTrait;

/**
 * Widget Contents Cache Model
 *
 * @author Bernard Paquier <pro@bernard-paquier.fr>
 */
class WidgetCacheBase
{
    use AccessTrait;
    use CacheTrait;
    use DefinitionTrait;
    use OptionsTrait;
    use ParametersTrait;

    //==============================================================================
    //      CONSTRUCTOR
    //==============================================================================

    /**
     * Class Cosntructor
     *
     * @param Widget $widget
     */
    public function __construct(Widget $widget = null)
    {
        if ($widget) {
            $this->setDefinition($widget);
        }
    }

    /**
     * Setup Widget Definition
     *
     * @param Widget $widget
     *
     * @return $this
     */
    public function setDefinition(Widget $widget) : self
    {
        $this
            ->setService($widget->getService())
            ->setType($widget->getType())
            ->setName($widget->getName())
            ->setDescription($widget->getName())
            ->setTitle($widget->getTitle())
            ->setSubTitle($widget->getSubTitle())
            ->setIcon($widget->getIcon())
            ->setOrigin($widget->getOrigin())
            ->setOptions($widget->getOptions())
        ;

        return $this;
    }
}
