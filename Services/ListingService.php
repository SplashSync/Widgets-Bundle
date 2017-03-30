<?php

namespace Splash\Widgets\Services;

use ArrayObject;

use Splash\Widgets\Models\Widget;

use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/*
 * Widget Factory Service
 */
class ListingService 
{
//====================================================================//
//  GENERIC WIDGETS LISTING TAGS
//====================================================================//
    
    const ALL_WIDGETS               =   "splash.widgets.list.all";
    const USER_WIDGETS              =   "splash.widgets.list.user";
    const NODES_WIDGETS             =   "splash.widgets.list.nodes";
    const STATS_WIDGETS             =   "splash.widgets.list.stats";
    const DEMO_WIDGETS              =   "splash.widgets.list.demo";
    
    /**
     * @var \Symfony\Component\EventDispatcher\EventDispatcher
     */
    public $Dispatcher;
    
    /*
     *  Fault String
     */
    public $fault_str;    

//====================================================================//
//  CONSTRUCTOR
//====================================================================//
    
    /**
     *      @abstract    Class Constructor
     */    
    public function __construct(EventDispatcherInterface $EventDispatcher) { 
        
        //====================================================================//
        // Link to Event Dispatcher Services
        $this->Dispatcher   =   $EventDispatcher;  
        
        return True;
    }    

//====================================================================//
// *******************************************************************//
//  WIDGET LISTING FUNCTIONS
// *******************************************************************//
//====================================================================//

    /**
     * Get Widgets List
     *
     * @return ArrayCollection
     */
    public function getList($Mode = self::USER_WIDGETS)
    {
        
        //====================================================================//
        // Execute Listing Event
        $List = $this->Dispatcher->dispatch($Mode, new GenericEvent() );

        foreach ($List->getArguments() as $Widget) {
            
        dump($Widget);
            
            if ( !is_a($Widget, Widget::class)  ) {
                throw new \Exception("Listed Widget is not of Appropriate Type (" . get_class($Widget) . ")");
            }
            
        }
        
        return $List;
    }
    
}