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

use Doctrine\ORM\Mapping                        as ORM;

/**
 * @abstract Widget Parameters Trait 
 * 
 * @author Bernard Paquier <pro@bernard-paquier.fr>
 */
trait ParametersTrait
{
    
    //==============================================================================
    //      Variables  
    //==============================================================================
    
    /**
     * @abstract    Widget Parameters Array
     * @var         array
     * @ORM\Column(name="Parameters", type="array")
     */
    protected $parameters = array();  
    
    //==============================================================================
    //      Getters & Setters  
    //==============================================================================
        /**
     * Set Parameters
     * 
     * @param   $parameters
     * 
     * @return  Widget
     */
    public function setParameters(array $parameters)
    {
        $this->parameters = $parameters;
        
        return $this;
    }
    
    /**
     * Get Parameters
     *
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }
}
