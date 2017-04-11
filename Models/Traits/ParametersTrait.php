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
    
    private $PRESETS               =   array(
        
        "Y"     =>  [
            "DateStart" => "first day of january this year",          
            "DateEnd" => "", 
            "GroupBy" => "M"
            ],
        "M"     =>  [
            "DateStart" => "first day of this month",          
            "DateEnd" => "", 
            "GroupBy" => "D"
            ],
        "W"     =>  [
            "DateStart" => "last monday",          
            "DateEnd" => "next sunday", 
            "GroupBy" => "D"
            ],
        "D"     =>  [
            "DateStart" => "",          
            "DateEnd" => "", 
            "GroupBy" => "H"
            ],
        "PY"     =>  [
            "DateStart" => "first day of january last year",          
            "DateEnd" => "last day of december last year", 
            "GroupBy" => "M"
            ],
        "PM"     =>  [
            "DateStart" => "first day of last month",          
            "DateEnd" => "last day of last month", 
            "GroupBy" => "D"
            ],
        "PW"     =>  [
            "DateStart" => "last week last monday",          
            "DateEnd" => "last sunday", 
            "GroupBy" => "D"
            ],
        "PD"     =>  [
            "DateStart" => "-1 day",          
            "DateEnd" => "-1 day", 
            "GroupBy" => "H"
            ],
        
    );
        
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
    //      Dates Presets Management  
    //==============================================================================
    
    /**
     * @abstract Verify Preset Name is Valid
     *
     * @param string $Preset Dates Preset Type
     * 
     * @return bool
     */
    public function isPreset($Preset) : bool
    {
        if ( !array_key_exists($Preset, $this->PRESETS) ) {
            return False;
        }
        return True;
    }
        
    /**
     * @abstract Get Dates Array From Presets
     *
     * @param string $Preset Dates Preset Type
     * 
     * @return array
     */
    public function getDatesArray($Preset = "M")
    {
        //==============================================================================
        //  Check If Preset Dates Mode Exists
        if ( !$this->isPreset($Preset) ) {
            return array();
        }
        //==============================================================================
        //  Prepare Dates
        $DateStart  =   new \DateTime($this->PRESETS[$Preset]["DateStart"]);
        $DateStart->setTime(0, 0, 0);
        $DateEnd    =   new \DateTime($this->PRESETS[$Preset]["DateEnd"]);
        $DateEnd->setTime(23, 59, 59);        
        $GroupBy    =   $this->PRESETS[$Preset]["GroupBy"];
        
        //==============================================================================
        //  Return Dates Array
        return  [
            "DateStart"     =>      $DateStart,
            "DateEnd"       =>      $DateEnd,
            "GroupBy"       =>      $GroupBy,
        ];

    }    
    
    /**
     * Set Parameter
     * 
     * @param   string  $key
     * @param   mixed   $value
     * 
     * @return  Widget
     */
    public function setParameter(string $key, $value)
    {
        $this->parameters[$key] = $value;
        
        return $this;
    }
    
    
    
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
     * @abstract Get Parameters
     * 
     * @param bool $WithDates Transform Date Preset To Dates
     * 
     * @return array
     */
    public function getParameters($withDates = False)
    {
        if ($withDates && isset($this->parameters["DatePreset"])) {
            return array_merge($this->parameters, $this->getDatesArray($this->parameters["DatePreset"]));
        } 
        return $this->parameters;
    }
}
