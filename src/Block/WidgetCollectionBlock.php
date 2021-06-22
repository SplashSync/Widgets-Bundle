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

namespace Splash\Widgets\Block;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ObjectRepository;
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
 * Sonata Block to render a Widget Collection
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
     * @var ObjectRepository
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
     *
     * @throws Exception
     */
    public function __construct(
        string $name,
        EngineInterface $templating,
        EntityManager $manager,
        RequestStack $requestStack
    ) {
        parent::__construct($name, $templating);

        $this->manager = $manager;
        $this->repository = $manager->getRepository(WidgetCollection::class);
        $request = $requestStack->getCurrentRequest();
        if (null === $request) {
            throw new Exception("Unable to Load Current Request");
        }
        $this->request = $request;
    }

    /**
     * {@inheritdoc}
     */
    public function configureSettings(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(array(
            'url' => false,
            'title' => 'Splash Widget Collection Block',
            'collection' => 'demo-block',
            'channel' => 'demo',
            'template' => 'SplashWidgetsBundle:Blocks:Collection.html.twig',
            'options' => array(),
            'editable' => true,
            'menu' => true,
        ));
    }

    /**
     * @param FormMapper     $formMapper
     * @param BlockInterface $block
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function buildEditForm(FormMapper $formMapper, BlockInterface $block): void
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
     *
     * @throws ORMException
     */
    public function execute(BlockContextInterface $blockContext, Response $response = null): ?Response
    {
        //==============================================================================
        // Get Block Settings
        $settings = $blockContext->getSettings();
        //==============================================================================
        // Load Collection from DataBase
        /** @var null|WidgetCollection $collection */
        $collection = $this->repository->findOneBy(array("type" => $settings["collection"]));
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
        $edit = (bool)(($settings["editable"] & ($this->request->get("widget-edit") == $collection->getId())));

        foreach ($collection->getWidgets() as &$widget) {
            // Merge Edition Options
            $widget->mergeOptions(array(
                "Editable" => $settings["editable"],
                "EditMode" => $this->request->get("widget-edit") == $collection->getId(),
            ));
            // Merge Global Options
            $widget->mergeOptions($settings["options"]);
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
    public function getBlockMetadata($code = null): Metadata
    {
        return new Metadata(
            $this->getName(),
            (!is_null($code) ? $code : $this->getName()),
            null,
            'SplashWidgetsBundle',
            array(
                'class' => 'fa fa-television',
            )
        );
    }
}
