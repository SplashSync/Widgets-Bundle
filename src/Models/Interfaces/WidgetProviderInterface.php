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

namespace Splash\Widgets\Models\Interfaces;

use Splash\Widgets\Entity\Widget;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Widget provider Interface
 */
interface WidgetProviderInterface
{
    /**
     * Read Widget Contents
     *
     * @param string $type       Widgets Type Identifier
     * @param array  $parameters Widget Parameters
     *
     * @return null|Widget
     */
    public function getWidget(string $type, array $parameters = null): ?Widget;

    /**
     * Return Widget Options Array
     *
     * @param string $type Widgets Type Identifier
     *
     * @return array
     */
    public function getWidgetOptions(string $type) : array;

    /**
     * Update Widget Options Array
     *
     * @param string $type    Widgets Type Identifier
     * @param array  $options Updated Options
     *
     * @return bool
     */
    public function setWidgetOptions(string $type, array $options) : bool;

    /**
     * Return Widget Parameters Array
     *
     * @param string $type Widgets Type Identifier
     *
     * @return array
     */
    public function getWidgetParameters(string $type) : array;

    /**
     * Update Widget Parameters Array
     *
     * @param string $type       Widgets Type Identifier
     * @param array  $parameters Updated Parameters
     *
     * @return bool
     */
    public function setWidgetParameters(string $type, array $parameters) : bool;

    /**
     * Return Widget Parameters Fields Array
     *
     * @param FormBuilderInterface $builder
     * @param string               $type    Widgets Type Identifier
     */
    public function populateWidgetForm(FormBuilderInterface $builder, string $type) : void;
}
