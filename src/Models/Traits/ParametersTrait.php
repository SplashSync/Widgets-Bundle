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
    
    static $PRESETS               =   array(
        
        "Y"     =>  [
            "DateStart"     => "first day of january this year",          
            "DateEnd"       => "", 
            "DateFormat"    => "Y-m", 
            "GroupBy"       => "m"
            ],
        "M"     =>  [
            "DateStart"     => "first day of this month",          
            "DateEnd"       => "", 
            "DateFormat"    => "Y-m-d", 
            "GroupBy"       => "d"
            ],
        "W"     =>  [
            "DateStart"     => "last monday",          
            "DateEnd"       => "next sunday", 
            "DateFormat"    => "Y-m-d", 
            "GroupBy"       => "d"
            ],
        "D"     =>  [
            "DateStart"     => "",          
            "DateEnd"       => "", 
            "DateFormat"    => "Y-m-d H:00", 
            "GroupBy"       => "h"
            ],
        "LM"     =>  [
            "DateStart"     => "-1 month",          
            "DateEnd"       => "", 
            "DateFormat"    => "Y-m-d", 
            "GroupBy"       => "d"
            ],
        "LW"     =>  [
            "DateStart"     => "-1 week",          
            "DateEnd"       => "", 
            "DateFormat"    => "Y-m-d", 
            "GroupBy"       => "d"
            ],
        "LY"     =>  [
            "DateStart"     => "-1 year",          
            "DateEnd"       => "", 
            "DateFormat"    => "Y-m", 
            "GroupBy"       => "m"
            ],
        "L2W"     =>  [
            "DateStart"     => "-2 week",          
            "DateEnd"       => "", 
            "DateFormat"    => "Y-m-d", 
            "GroupBy"       => "d"
            ],
        "PY"     =>  [
            "DateStart"     => "first day of january last year",          
            "DateEnd"       => "last day of december last year", 
            "DateFormat"    => "Y-m", 
            "GroupBy"       => "m"
            ],
        "PM"     =>  [
            "DateStart"     => "first day of last month",          
            "DateEnd"       => "last day of last month", 
            "DateFormat"    => "Y-m-d", 
            "GroupBy"       => "d"
            ],
        "PW"     =>  [
            "DateStart"     => "last week last monday",          
            "DateEnd"       => "last sunday", 
            "DateFormat"    => "Y-m-d", 
            "GroupBy"       => "d"
            ],
        "PD"     =>  [
            "DateStart"     => "-1 day",          
            "DateEnd"       => "-1 day", 
            "DateFormat"    => "Y-m-d H:00", 
            "GroupBy"       => "h"
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
    public static function isPreset($Preset) : bool
    {
        if ( !array_key_exists($Preset, static::$PRESETS) ) {
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
    public static function getDatesArray($Preset = "M")
    {
        //==============================================================================
        //  Check If Preset Dates Mode Exists
        if ( !self::isPreset($Preset) ) {
            return array();
        }
        //==============================================================================
        //  Prepare Dates
        $DateStart  =   new \DateTime(static::$PRESETS[$Preset]["DateStart"]);
        $DateStart->setTime(0, 0, 0);
        $DateEnd    =   new \DateTime(static::$PRESETS[$Preset]["DateEnd"]);
        $DateEnd->setTime(23, 59, 59);        
        $DateFormat =   static::$PRESETS[$Preset]["DateFormat"];
        $GroupBy    =   static::$PRESETS[$Preset]["GroupBy"];
        
        //==============================================================================
        //  Return Dates Array
        return  [
            "DateStart"     =>      $DateStart,
            "DateEnd"       =>      $DateEnd,
            "DateFormat"    =>      $DateFormat,
            "GroupBy"       =>      $GroupBy,
        ];

    }    
    
    /**
     * @abstract Add Dates Array From Presets
     *
     * @param array $Parameters with Dates Preset     
     * 
     * @return array
     */
    public static function addDatesPresets($Parameters = array())
    {
        if ( isset($Parameters["DatePreset"]) && self::isPreset($Parameters["DatePreset"])) {
            return array_merge($Parameters, self::getDatesArray($Parameters["DatePreset"]) );
        }
        return $Parameters;
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
