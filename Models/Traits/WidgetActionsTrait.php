<?php

/*
 * This file is part of the Splash Sync project.
 *
 * (c) Bernard Paquier <pro@bernard-paquier.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Splash\Widgets\Models\Traits;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @abstract Widget Actions Trait - Define Widget Actions Js Functions 
 * 
 * @author Bernard Paquier <pro@bernard-paquier.fr>
 */
trait WidgetActionsTrait
{
    
    /**
     * @var string
     */
    protected $actions   =   array(
        "insert"                =>  "OWReports_InsertWidget",
        "delete"                =>  "OWReports_DeleteWidget",
        "ordering"              =>  "OWReports_UpdateWidgetOrdering",
        "update_parameters"     =>  "OWReports_UpdateWidgetParameters",
        "update_options"        =>  "OWReports_UpdateWidgetOptions",
    );

    /**
     * @var string
     */
    protected $parent;

    //====================================================================//
    // *******************************************************************//
    //  Widget Getter & Setter Functions
    // *******************************************************************//
    //====================================================================//
    
    /**
     * Set Widget Actions JS Functions
     * 
     * @param   string      $type
     * @param   string      $function
     * 
     * @return  WidgetActionsTrait
     */
    public function setAction(string $type, string $function)
    {
        if (isset($this->actions[$type])) {
            $this->actions[$type] = $function;
        } 
        return $this;
    }
    
    /**
     * Get Widget Action JS Function
     * 
     * @param   string      $type
     * 
     * @return  String
     */
    public function getAction($type)
    {
        if (isset($this->actions[$type])) {
            return $this->actions[$type];
        } 
        return null;
    } 
    
    /**
     * Get Widget Actions JS Functions
     * 
     * @return  array
     */
    public function getActions()
    {
        return $this->actions;
    } 
    
    
    /**
     * Set Widget Parent Collection
     *
     * @param string $Parent
     *
     * @return  WidgetActionsTrait
     */
    public function setParent($Parent)
    {
        $this->parent = $Parent;

        return $this;
    }

    /**
     * Get Widget Parent Collection
     * 
     * @return mixed
     */
    public function getParent()
    {
        return $this->parent;
    }
}
