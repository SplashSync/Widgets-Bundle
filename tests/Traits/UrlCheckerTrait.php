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

use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;

/**
 * Verify Pages Loading is Ok
 */
trait UrlCheckerTrait
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var Client
     */
    private $client;

    /**
     * Generate Relative Url
     *
     * @param string $route
     * @param array  $parameters
     *
     * @return string
     */
    public function generateUrl(string $route, array $parameters) : string
    {
        //====================================================================//
        // Link to Symfony Router
        if (!isset($this->router)) {
            $container = $this->client->getContainer();
            $this->assertNotNull($container);
            $router = $container->get('router');
            $this->assertInstanceOf(RouterInterface::class, $router);
            $this->router = $router;
        }
        //====================================================================//
        // Generate Url
        $this->assertInstanceOf(RouterInterface::class, $this->router);

        return $this->router->generate($route, $parameters);
    }

    /**
     * Verify if an Url Works and return Crawler
     *
     * @param string $url
     * @param string $method
     *
     * @return Crawler
     */
    public function assertUrlWorks(string $url, string $method = "GET") : Crawler
    {
        $this->ensureClientIsLoaded();

        //====================================================================//
        // Execute Client Request
        $this->client->followRedirects();
        $this->client->setMaxRedirects(3);
        $crawler = $this->client->request($method, $url);

        //====================================================================//
        // Verify Response
        $response = $this->client->getResponse();
        $this->assertInstanceOf(Response::class, $response);
        if (!$response->isSuccessful()) {
            var_dump(substr((string) $response->getContent(), 0, 1000));
        }
        $this->assertTrue(
            $response->isSuccessful(),
            "This Url Fail : ".$url." Status Code : ".$response->getStatusCode()
        );

        return $crawler;
    }

    /**
     * Verify if an Url Fail and return Crawler
     *
     * @param string $url
     * @param string $method
     *
     * @return Crawler
     */
    public function assertUrlFail(string $url, string $method = "GET") : Crawler
    {
        $this->ensureClientIsLoaded();

        //====================================================================//
        // Execute Client Request
        $this->client->followRedirects();
        $this->client->setMaxRedirects(3);
        $crawler = $this->client->request($method, $url);

        //====================================================================//
        // Verify Response
        $response = $this->client->getResponse();
        $this->assertInstanceOf(Response::class, $response);
        $this->assertFalse(
            $response->isSuccessful(),
            "This Url Should Fail but Works : ".$url." Status Code : ".$response->getStatusCode()
        );

        return $crawler;
    }

    /**
     * Verify if an Url Redirect to An Url and return Crawler
     *
     * @param string      $url
     * @param null|string $target
     * @param string      $method
     *
     * @return Crawler
     */
    public function assertUrlRedirects(string $url, string $target = null, string $method = "GET") : Crawler
    {
        $this->ensureClientIsLoaded();

        //====================================================================//
        // Execute Client Request
        $this->client->followRedirects(false);
        $crawler = $this->client->request($method, $url);

        //====================================================================//
        // Verify Response
        $response = $this->client->getResponse();
        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertTrue(
            $response->isRedirection(),
            "This Url Should Redirect but Doesn't : ".$url." Status Code : ".$response->getStatusCode()
        );

        if ($target) {
            $this->assertEquals(
                parse_url($response->getTargetUrl(), PHP_URL_PATH),
                parse_url($target, PHP_URL_PATH),
                "This Url Should Redirect to ".$target
                ." but redirect to : ".$url." Status Code : ".$response->getStatusCode()
            );
        }

        return $crawler;
    }

    /**
     * Verify if an Submit of a Form Works
     *
     * @param mixed $form
     */
    public function assertSubmitWorks($form) : void
    {
        $this->ensureClientIsLoaded();

        //====================================================================//
        // submit that form
        $this->client->followRedirects();
        $this->client->submit($form);
        //====================================================================//
        // Verify Submit Was Successful
        $this->client->followRedirects();
        $response = $this->client->getResponse();
        $this->assertInstanceOf(Response::class, $response);
        $this->assertTrue($response->isSuccessful(), "Form Submit Failled");
    }

    /**
     * Verify if a Route Works and return Crawler
     *
     * @param string $route
     * @param array  $parameters
     * @param string $method
     *
     * @return Crawler
     */
    public function assertRouteWorks(string $route, array $parameters = array(), string $method = "GET") : Crawler
    {
        $this->ensureClientIsLoaded();

        return $this->assertUrlWorks($this->generateUrl($route, $parameters), $method);
    }

    /**
     * Verify if a Route Fails and return Crawler
     *
     * @param string $route
     * @param array  $parameters
     * @param string $method
     *
     * @return Crawler
     */
    public function assertRouteFail(string $route, array $parameters = array(), string $method = "GET") : Crawler
    {
        $this->ensureClientIsLoaded();

        return $this->assertUrlFail($this->generateUrl($route, $parameters), $method);
    }

    /**
     * Verify if A Route Redirect to Another
     *
     * @param string      $route
     * @param array       $parameters
     * @param null|string $target
     * @param array       $targetParams
     *
     * @return Crawler
     */
    public function assertRouteRedirects(
        string $route,
        array $parameters = array(),
        string $target = null,
        array $targetParams = array()
    ): Crawler {
        $this->ensureClientIsLoaded();

        return $this->assertUrlRedirects(
            $this->generateUrl($route, $parameters),
            $this->generateUrl((string) $target, $targetParams)
        );
    }

    /**
     * Ensure BrowserKit Client is Loaded
     */
    private function ensureClientIsLoaded() : void
    {
        $this->assertTrue(isset($this->client), "Test Client Not Found ( this->client )");
        $this->assertInstanceOf(
            Client::class,
            $this->client,
            "Invalid Test Client Not Found ( ".get_class($this->client)." )"
        );
    }
}
