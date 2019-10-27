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

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;

/**
 * Widgets Demo Symfony App Kernel
 */
class AppKernel extends Kernel
{
    /**
     * {@inheritdoc}
     */
    public function registerBundles()
    {
        //==============================================================================
        // SYMFONY CORE
        $bundles[] = new Symfony\Bundle\FrameworkBundle\FrameworkBundle();
        $bundles[] = new Symfony\Bundle\SecurityBundle\SecurityBundle();
        $bundles[] = new Symfony\Bundle\TwigBundle\TwigBundle();
        $bundles[] = new Symfony\WebpackEncoreBundle\WebpackEncoreBundle();
        $bundles[] = new Symfony\Bundle\MonologBundle\MonologBundle();
        //==============================================================================
        // DOCTRINE CORE
        $bundles[] = new Doctrine\Bundle\DoctrineBundle\DoctrineBundle();
        //==============================================================================
        // KNP BUNDLES
        $bundles[] = new Knp\Bundle\TimeBundle\KnpTimeBundle();
        $bundles[] = new Knp\Bundle\MenuBundle\KnpMenuBundle();
        //==============================================================================
        // SONATA BUNDLES
        $bundles[] = new Sonata\CoreBundle\SonataCoreBundle();
        $bundles[] = new Sonata\BlockBundle\SonataBlockBundle();

        //==============================================================================
        // SPLASH WIDGETS
        $bundles[] = new Splash\Widgets\SplashWidgetsBundle();

        //==============================================================================
        // TEST & DEV BUNDLES
        if (in_array($this->getEnvironment(), array('dev', 'test'), true)) {
            $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();

            if ('dev' === $this->getEnvironment()) {
                $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            }

            if (('dev' === $this->getEnvironment()) && class_exists("\\Symfony\\Bundle\\WebServerBundle\\WebServerBundle")) {
                $bundles[] = new Symfony\Bundle\WebServerBundle\WebServerBundle();
            }
        }

        return $bundles;
    }

    /**
     * {@inheritdoc}
     */
    public function getRootDir()
    {
        return __DIR__;
    }

    /**
     * {@inheritdoc}
     */
    public function getCacheDir()
    {
        return dirname(__DIR__).'/var/cache/'.$this->getEnvironment();
    }

    /**
     * {@inheritdoc}
     */
    public function getLogDir()
    {
        return dirname(__DIR__).'/var/logs';
    }

    /**
     * {@inheritdoc}
     */
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        if ("test" == $this->getEnvironment()) {
            $loader->load($this->getRootDir().'/config_test.yml');
        } else {
            $loader->load($this->getRootDir().'/config.yml');
        }
    }
}
