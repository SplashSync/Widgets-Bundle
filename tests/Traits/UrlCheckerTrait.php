<?php

namespace Splash\Widgets\Tests\Traits;

use Symfony\Component\Routing\RouterInterface;
use Symfony\Bundle\FrameworkBundle\Client;

/**
 * @abstract    Verify Pages Loading is Ok
 */
trait UrlCheckerTrait {
    
    /**
     * @var RouterInterface
     */
    private $router;

    public function generateUrl( $Route, $Parameters, $Locale )
    {
        //====================================================================//
        // Link to Symfony Router
        if ( empty($this->router) ) {
            $this->router   =   $this->client->getContainer()->get('router');
        }
        //====================================================================//
        // Generate Url
        return $this->router->generate($Route, $Parameters );
    }
    
    private function ensureClientIsLoaded()
    {
        $this->assertTrue(isset($this->client),                 "Test Client Not Found ( this->client )");
        $this->assertInstanceOf(Client::class , $this->client,  "Invalid Test Client Not Found ( " . get_class($this->client) . " )");  
    }  
    
    public function assertUrlWorks($Url, $Method = "GET")
    {
        $this->ensureClientIsLoaded();
        
        //====================================================================//
        // Execute Client Request
        $this->client->followRedirects();
        $this->client->setMaxRedirects(3);
        $Crawler = $this->client->request($Method, $Url);

        if ( !$this->client->getResponse()->isSuccessful() ) {
            dump( substr($this->client->getResponse()->getContent(), 0, 1000));
        }
        //====================================================================//
        // Verify Response
        $this->assertTrue($this->client->getResponse()->isSuccessful(), "This Url Fail : " . $Url . " Status Code : " . $this->client->getResponse()->getStatusCode());
        
        return $Crawler;
    }  
    
    public function assertUrlFail($Url, $Method = "GET")
    {
        $this->ensureClientIsLoaded();
        
        //====================================================================//
        // Execute Client Request
        $this->client->followRedirects();
        $this->client->setMaxRedirects(3);
        $Crawler    =   $this->client->request($Method, $Url);

        //====================================================================//
        // Verify Response
        $this->assertFalse($this->client->getResponse()->isSuccessful(), "This Url Should Fail but Works : " . $Url . " Status Code : " . $this->client->getResponse()->getStatusCode());

        return $Crawler;
    }      

    public function assertUrlRedirects($Url, $Target = Null, $Method = "GET")
    {
        $this->ensureClientIsLoaded();
        
        //====================================================================//
        // Execute Client Request
        $this->client->followRedirects(False);
        $Crawler = $this->client->request($Method, $Url);

        //====================================================================//
        // Verify Response
        $this->assertTrue($this->client->getResponse()->isRedirection(), "This Url Should Redirect but Doesn't : " . $Url . " Status Code : " . $this->client->getResponse()->getStatusCode());
        
        if ( $Target ) {
//            $this->client->followRedirect();
            $this->assertEquals(
                    parse_url($this->client->getResponse()->getTargetUrl(), PHP_URL_PATH), 
                    parse_url($Target, PHP_URL_PATH), 
                    "This Url Should Redirect to " . $Target . " but redirect to : " . $Url . " Status Code : " . $this->client->getResponse()->getStatusCode());
        } 
        
        return $Crawler;
    }      
    
    public function assertSubmitWorks($Form)
    {
        $this->ensureClientIsLoaded();
     
        //====================================================================//
        // submit that form
        $this->client->followRedirects();
        $this->client->submit($Form);
        //====================================================================//
        // Verify Submit Was Successfull
        $this->client->followRedirects();
        $this->assertTrue($this->client->getResponse()->isSuccessful(), "Form Submit Failled");
    }  

    public function assertRouteWorks($Route, $Parameters = [], $Locale = Null, $Method = "GET")
    {
        $this->ensureClientIsLoaded();
        
        if ( !is_null($Locale) ) {
            return $this->assertUrlWorks($this->generateUrl($Route, $Parameters, $Locale ), $Method);
        } 
        
        //====================================================================//
        // Get List Of Locales
        $Locales    =   static::$kernel->getContainer()->getParameter('locales');
        $this->assertNotEmpty($Locales);
        foreach($Locales as $Locale) {
            $Crawler = $this->assertUrlWorks($this->generateUrl($Route, $Parameters, $Locale ), $Method);
        }
        return $Crawler;
    }  
    
    public function assertRouteFail($Route, $Parameters = [], $Locale = Null, $Method = "GET")
    {
        $this->ensureClientIsLoaded();
        
        if ( !is_null($Locale) ) {
            return $this->assertUrlFail($this->generateUrl($Route, $Parameters, $Locale ), $Method);
        } 
        
        //====================================================================//
        // Get List Of Locales
        $Locales    =   static::$kernel->getContainer()->getParameter('locales');
        $this->assertNotEmpty($Locales);
        foreach($Locales as $Locale) {
            $Crawler = $this->assertUrlFail($this->generateUrl($Route, $Parameters, $Locale ), $Method);
        }
        return $Crawler;
    } 

    public function assertRouteRedirects($Route, $Parameters = [], $Locale = Null, $Target = Null, $TargetParams = [])
    {
        $this->ensureClientIsLoaded();
        
        if ( !is_null($Locale) ) {
            return $this->assertUrlRedirects(
                    $this->generateUrl($Route,  $Parameters,    $Locale ),
                    $this->generateUrl($Target, $TargetParams,  $Locale )
                    );
        } 
        
        //====================================================================//
        // Get List Of Locales
        $Locales    =   static::$kernel->getContainer()->getParameter('locales');
        $this->assertNotEmpty($Locales);
        foreach($Locales as $Locale) {
            $Crawler    =   $this->assertUrlRedirects(
                    $this->generateUrl($Route,  $Parameters,    $Locale ),
                    $this->generateUrl($Target, $TargetParams,  $Locale )
                    );
        }
        return $Crawler;
    } 
    
}
