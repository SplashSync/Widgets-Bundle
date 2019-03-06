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

namespace Splash\Widgets\Tests\Traits;

use Doctrine\ORM\EntityManager;
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
     * @var EntityManager
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
     * @return EntityManager
     */
    protected function getEntityManager() : EntityManager
    {
        if (!isset($this->entityManager)) {
            $this->entityManager = $this->getContainer()->get('doctrine')->getManager();
            if (!($this->entityManager instanceof EntityManager)) {
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
            $this->manager = $this->getContainer()->get('Splash.Widgets.Manager');
            if (!($this->manager instanceof ManagerService)) {
                throw new Exception("Unable to Load Widget Manager Service");
            }
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
            $this->factory = $this->getContainer()->get('Splash.Widgets.Factory');
            if (!($this->factory instanceof ManagerService)) {
                throw new Exception("Unable to Load Widget Factory Service");
            }
        }

        return $this->factory;
    }
}
