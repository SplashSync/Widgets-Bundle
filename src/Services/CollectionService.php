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

namespace Splash\Widgets\Services;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use Exception;
use Splash\Widgets\Entity\Widget;
use Splash\Widgets\Entity\WidgetCollection;
use Splash\Widgets\Models\Interfaces\WidgetProviderInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Widgets Collection Service
 */
class CollectionService implements WidgetProviderInterface
{
    /**
     * WidgetFactory Service
     *
     * @var FactoryService
     */
    private $factory;

    /**
     * Widgets Collections Repository
     *
     * @var ObjectRepository
     */
    private $repository;

    /**
     * Symfony Entity Manager
     *
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * Service Container
     *
     * @var ContainerInterface
     */
    private $container;

    /**
     * Widget Collection
     *
     * @var WidgetCollection
     */
    private $collection;

    //====================================================================//
    //  CONSTRUCTOR
    //====================================================================//

    /**
     * Class Constructor
     *
     * @param EntityManagerInterface $doctrine
     * @param FactoryService         $widgetFactory
     * @param ContainerInterface     $container
     */
    public function __construct(
        EntityManagerInterface $doctrine,
        FactoryService $widgetFactory,
        ContainerInterface $container
    ) {
        //====================================================================//
        // Link to WidgetFactory Service
        $this->factory = $widgetFactory;
        //====================================================================//
        // Link to Entity Manager
        $this->entityManager = $doctrine;
        //====================================================================//
        // Link to Widget Repository
        $this->repository = $doctrine->getRepository(WidgetCollection::class);
        //====================================================================//
        // Link to Service Container
        $this->container = $container;
    }

    /**
     * Load Widget definition
     *
     * @param string $type Widgets Type Identifier
     *
     * @return null|Widget
     */
    public function getDefinition(string $type) : ?Widget
    {
        //====================================================================//
        // Check Type is On Right Format
        $widgetId = explode("@", $type);
        if (2 != count($widgetId)) {
            return null;
        }
        //====================================================================//
        // Load Widget Collection
        /** @var WidgetCollection $collection */
        $collection = $this->repository->find($widgetId[1]);
        $this->collection = $collection;
        //====================================================================//
        // Load Widget Definition from Collection
        return $this->collection->getWidget($widgetId[0]);
    }

    /**
     * Read Widget Contents
     *
     * @param string     $type       Widgets Type Identifier
     * @param null|array $parameters Widget Parameters
     *
     * @return null|Widget
     */
    public function getWidget(string $type, array $parameters = null): ?Widget
    {
        //==============================================================================
        // Load Widget Definition
        $definition = $this->getDefinition($type);
        if (!$definition) {
            return $this->factory->buildErrorWidget(
                "Collections",
                $type,
                "Unable to Find Widget Definition"
            );
        }
        //==============================================================================
        // Load Widget Provider Service
        if (!$this->container->has($definition->getService())) {
            return $this->factory->buildErrorWidget(
                $definition->getService(),
                $type,
                "Unable to Load Widget Provider"
            );
        }
        //==============================================================================
        // Load Widget Provider Service
        $sfService = $this->container->get($definition->getService());
        if (!($sfService instanceof WidgetProviderInterface)) {
            return $this->factory->buildErrorWidget(
                $definition->getService(),
                $type,
                "Unable to Load Widget Provider"
            );
        }
        //==============================================================================
        // Load Widget Parameters
        if (is_null($parameters)) {
            $parameters = $definition->getParameters(true);
        }
        //==============================================================================
        // Read Widget Contents
        $widget = $sfService->getWidget($definition->getType(), $parameters);
        //==============================================================================
        // Validate Widget Contents
        if (empty($widget) || !($widget instanceof Widget)) {
            $widget = $this->factory->buildErrorWidget(
                $definition->getService(),
                $type,
                "An Error Occurred During Widget Loading"
            );
        }
        //==============================================================================
        // Override Widget Options
        if (!empty($definition->getOptions())) {
            $widget->setOptions($definition->getOptions());
        }
        //==============================================================================
        // Override Widget Service & Type
        $widget->setService($this->collection->getService());
        $widget->setType($type);

        return $widget;
    }

    /**
     * Return Widget Options Array
     *
     * @param string $type Widgets Type Identifier
     *
     * @return array
     */
    public function getWidgetOptions(string $type) : array
    {
        //==============================================================================
        // Load Widget Definition
        $definition = $this->getDefinition($type);
        if (!$definition) {
            return array();
        }

        return $definition->getOptions();
    }

    /**
     * Update Widget Options Array
     *
     * @param string $type    Widgets Type Identifier
     * @param array  $options Updated Options
     *
     * @return bool
     */
    public function setWidgetOptions(string $type, array $options) : bool
    {
        //==============================================================================
        // Load Widget Definition
        $definition = $this->getDefinition($type);
        if (!$definition) {
            return false;
        }
        //==============================================================================
        // Update Widget Options
        $definition->setOptions($options);
        $this->entityManager->flush();

        return true;
    }

    /**
     * Return Widget Parameters Array
     *
     * @param string $type Widgets Type Identifier
     *
     * @return array
     */
    public function getWidgetParameters(string $type) : array
    {
        //==============================================================================
        // Load Widget Definition
        $definition = $this->getDefinition($type);
        if (!$definition) {
            return array();
        }

        return $definition->getParameters();
    }

    /**
     * Update Widget Parameters Array
     *
     * @param string $type       Widgets Type Identifier
     * @param array  $parameters Updated Parameters
     *
     * @return bool
     */
    public function setWidgetParameters(string $type, array $parameters) : bool
    {
        //==============================================================================
        // Load Widget Definition
        $definition = $this->getDefinition($type);
        if (!$definition) {
            return false;
        }

        $definition->setParameters($parameters);
        $this->entityManager->flush();

        return true;
    }

    /**
     * Return Widget Parameters Fields Array
     *
     * @param FormBuilderInterface $builder
     * @param string               $type    Widgets Type Identifier
     *
     * @throws Exception
     */
    public function populateWidgetForm(FormBuilderInterface $builder, string $type) : void
    {
        //==============================================================================
        // Load Widget Definition
        $definition = $this->getDefinition($type);
        if (!$definition) {
            return;
        }
        //==============================================================================
        // Ensure Provider Service Exists
        if (!$this->container->has($definition->getService())) {
            return;
        }
        //==============================================================================
        // Load Widget Provider Service
        $sfService = $this->container->get($definition->getService());
        if (!($sfService instanceof WidgetProviderInterface)) {
            $msg = "Widget Service Provider must Implement  (".WidgetProviderInterface::class.")";

            throw new Exception($msg);
        }
        //==============================================================================
        // Populate Form
        $sfService->populateWidgetForm($builder, $definition->getType());
    }
}
