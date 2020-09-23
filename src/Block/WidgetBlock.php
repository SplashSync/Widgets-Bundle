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

namespace Splash\Widgets\Block;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Sonata\BlockBundle\Block\Service\AbstractAdminBlockService;
use Sonata\BlockBundle\Meta\Metadata;
use Sonata\BlockBundle\Model\BlockInterface;
use Splash\Widgets\Models\Traits\ParametersTrait;
use Splash\Widgets\Services\FactoryService;
use Splash\Widgets\Services\ManagerService;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Sonata Block to render just a Widget
 */
class WidgetBlock extends AbstractAdminBlockService
{
    use ParametersTrait;

    /**
     * Splash Widgets Manager
     *
     * @var ManagerService
     */
    private $manager;

    /**
     * Splash Widgets Factory
     *
     * @var FactoryService
     */
    private $factory;

    /**
     * Class Constructor
     *
     * @param string          $name
     * @param EngineInterface $templating
     * @param ManagerService  $widgetsManager
     * @param FactoryService  $widgetFactory
     */
    public function __construct(string $name, EngineInterface $templating, ManagerService $widgetsManager, FactoryService $widgetFactory)
    {
        parent::__construct($name, $templating);

        $this->manager = $widgetsManager;
        $this->factory = $widgetFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function configureSettings(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(array(
            'service' => null,
            'type' => null,
            'template' => 'SplashWidgetsBundle:Blocks:Widget.html.twig',
            'parameters' => array(),
            'options' => array(),
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function buildEditForm(FormMapper $formMapper, BlockInterface $block): void
    {
        $formMapper->add('settings', 'sonata_type_immutable_array', array(
            'keys' => array(
                array('service',    'text', array('required' => true)),
                array('type',       'text', array('required' => true)),
            ),
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function execute(BlockContextInterface $blockContext, Response $response = null)
    {
        //==============================================================================
        // Get Block Settings
        $settings = $blockContext->getSettings();

        //==============================================================================
        // Merge Passed Rendering Options to Widget Options
        $options = array_merge($this->manager->getWidgetOptions($settings["service"], $settings["type"]), $settings["options"]);

        //==============================================================================
        // Merge Passed Parameters
        $parameters = array_merge($this->manager->getWidgetParameters($settings["service"], $settings["type"]), $settings["parameters"]);

        //==============================================================================
        // Render Response
        return $this->renderResponse($blockContext->getTemplate(), array(
            "Service" => $settings["service"],
            "Type" => $settings["type"],
            "Options" => $options,
            "Parameters" => $parameters,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockMetadata($code = null)
    {
        return new Metadata($this->getName(), (!is_null($code) ? $code : $this->getName()), null, 'SplashWidgetsBundle', array(
            'class' => 'fa fa-television',
        ));
    }
}
