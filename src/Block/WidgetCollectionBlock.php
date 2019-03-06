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

namespace Splash\Widgets\Block;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Exception;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Sonata\BlockBundle\Block\Service\AbstractAdminBlockService;
use Sonata\BlockBundle\Meta\Metadata;
use Sonata\BlockBundle\Model\BlockInterface;
use Splash\Widgets\Entity\WidgetCollection;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Bernard Paquier <eshop.bpaquier@gmail.com>
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class WidgetCollectionBlock extends AbstractAdminBlockService
{
    /**
     * @var EntityManager
     */
    private $manager;

    /**
     * Widget Collections Repository
     *
     * @var EntityRepository
     */
    private $repository;

    /**
     * Symfony Request
     *
     * @var Request
     */
    private $request;

    /**
     * @param string          $name
     * @param EngineInterface $templating
     * @param EntityManager   $manager
     * @param RequestStack    $requestStack
     */
    public function __construct(string $name, EngineInterface $templating, EntityManager $manager, RequestStack $requestStack)
    {
        parent::__construct($name, $templating);

        $this->manager = $manager;
        $this->repository = $manager->getRepository('SplashWidgetsBundle:WidgetCollection');
        $request = $requestStack->getCurrentRequest();
        if (null === $request) {
            throw new Exception("Unable to Load Current Request");
        }
        $this->request = $request;
    }

    /**
     * {@inheritdoc}
     */
    public function configureSettings(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'url' => false,
            'title' => 'Splash Widget Collection Block',
            'collection' => 'demo-block',
            'channel' => 'demo',
            'template' => 'SplashWidgetsBundle:Blocks:Collection.html.twig',
            'editable' => true,
            'menu' => true,
        ));
    }

    /**
     * {@inheritdoc}
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function buildEditForm(FormMapper $formMapper, BlockInterface $block)
    {
        $formMapper->add('settings', 'sonata_type_immutable_array', array(
            'keys' => array(
                array('title',      'text', array('required' => false)),
                array('collection', 'text', array('required' => true)),
                array('channel',    'text', array('required' => true)),
                array('editable',   'bool', array('required' => false)),
                array('menu',       'bool', array('required' => false)),
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
        // Load Collection from DataBase
        $collection = $this->repository->findOneByType($settings["collection"]);
        //==============================================================================
        // Create Collection if not found
        if (!$collection) {
            $collection = new WidgetCollection();
            $this->manager->persist($collection);
        }
        //==============================================================================
        // Update Collection Parameters
        $collection->setType($settings["collection"]);
        $this->manager->flush();
        //==============================================================================
        // Is Edit Mode?
        $edit = ($settings["editable"] & ($this->request->get("widget-edit") == $collection->getId())) ? true : false;

        foreach ($collection->getWidgets() as &$widget) {
            $widget->mergeOptions(array(
                "Editable" => $settings["editable"],
                "EditMode" => (($this->request->get("widget-edit") == $collection->getId()) ? true : false),
            ));
        }

        //==============================================================================
        // Render Response
        return $this->renderResponse($blockContext->getTemplate(), array(
            "Collection" => $collection,
            "Title" => $settings["title"],
            "Channel" => $settings["channel"],
            "Menu" => $settings["menu"],
            "Edit" => $edit,
            "Editable" => $settings["editable"],
        ), $response);
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
