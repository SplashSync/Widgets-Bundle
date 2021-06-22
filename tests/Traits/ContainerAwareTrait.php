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

namespace Splash\Widgets\Tests\Traits;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\Common\Persistence\ObjectManager;
use Exception;
use Splash\Widgets\Services\FactoryService;
use Splash\Widgets\Services\ManagerService;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Safe Load Symfony Service Container
 */
trait ContainerAwareTrait
{
    /**
     * Splash Widgets Manager
     *
     * @var ManagerService
     */
    private $manager;

    /**
     * @var FactoryService
     */
    private $factory;

    /**
     * Doctrine Entity Manager
     *
     * @var ObjectManager
     */
    private $entityManager;

    /**
     * Safe Load Symfony Service Container
     *
     * @throws Exception
     *
     * @return ContainerInterface
     */
    protected function getContainer() : ContainerInterface
    {
        $container = static::$kernel->getContainer();
        if (!($container instanceof ContainerInterface)) {
            throw new Exception("Unable to Load Service Container");
        }

        return $container;
    }

    /**
     * Safe Load Doctrine Entity Manager
     *
     * @throws Exception
     *
     * @return ObjectManager
     */
    protected function getEntityManager() : ObjectManager
    {
        if (!isset($this->entityManager)) {
            /** @var Registry $registry */
            $registry = $this->getContainer()->get('doctrine');
            $this->entityManager = $registry->getManager();
            if (!($this->entityManager instanceof ObjectManager)) {
                throw new Exception("Unable to Load Entity Manager Service");
            }
        }

        return $this->entityManager;
    }

    /**
     * Safe Load Widget Manager Service
     *
     * @throws Exception
     *
     * @return ManagerService
     */
    protected function getManager() : ManagerService
    {
        if (!isset($this->manager)) {
            $manager = $this->getContainer()->get('splash.widgets.manager');
            if (!($manager instanceof ManagerService)) {
                throw new Exception("Unable to Load Widget Manager Service");
            }
            $this->manager = $manager;
        }

        return $this->manager;
    }

    /**
     * Safe Load Widget Factory Service
     *
     * @throws Exception
     *
     * @return FactoryService
     */
    protected function getFactory() : FactoryService
    {
        if (!isset($this->factory)) {
            $factory = $this->getContainer()->get('splash.widgets.factory');
            if (!($factory instanceof FactoryService)) {
                throw new Exception("Unable to Load Widget Factory Service");
            }
            $this->factory = $factory;
        }

        return $this->factory;
    }
}
