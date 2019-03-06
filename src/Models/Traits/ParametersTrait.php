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

namespace Splash\Widgets\Models\Traits;

use Doctrine\ORM\Mapping                        as ORM;

/**
 * Widget Parameters Trait
 *
 * @author Bernard Paquier <pro@bernard-paquier.fr>
 */
trait ParametersTrait
{
    public static $presets = array(
        "Y" => array(
            "DateStart" => "first day of january this year",
            "DateEnd" => "",
            "DateFormat" => "Y-m",
            "GroupBy" => "m",
        ),
        "M" => array(
            "DateStart" => "first day of this month",
            "DateEnd" => "",
            "DateFormat" => "Y-m-d",
            "GroupBy" => "d",
        ),
        "W" => array(
            "DateStart" => "last monday",
            "DateEnd" => "next sunday",
            "DateFormat" => "Y-m-d",
            "GroupBy" => "d",
        ),
        "D" => array(
            "DateStart" => "",
            "DateEnd" => "",
            "DateFormat" => "Y-m-d H:00",
            "GroupBy" => "h",
        ),
        "LM" => array(
            "DateStart" => "-1 month",
            "DateEnd" => "",
            "DateFormat" => "Y-m-d",
            "GroupBy" => "d",
        ),
        "LW" => array(
            "DateStart" => "-1 week",
            "DateEnd" => "",
            "DateFormat" => "Y-m-d",
            "GroupBy" => "d",
        ),
        "LY" => array(
            "DateStart" => "-1 year",
            "DateEnd" => "",
            "DateFormat" => "Y-m",
            "GroupBy" => "m",
        ),
        "L2W" => array(
            "DateStart" => "-2 week",
            "DateEnd" => "",
            "DateFormat" => "Y-m-d",
            "GroupBy" => "d",
        ),
        "PY" => array(
            "DateStart" => "first day of january last year",
            "DateEnd" => "last day of december last year",
            "DateFormat" => "Y-m",
            "GroupBy" => "m",
        ),
        "PM" => array(
            "DateStart" => "first day of last month",
            "DateEnd" => "last day of last month",
            "DateFormat" => "Y-m-d",
            "GroupBy" => "d",
        ),
        "PW" => array(
            "DateStart" => "last week last monday",
            "DateEnd" => "last sunday",
            "DateFormat" => "Y-m-d",
            "GroupBy" => "d",
        ),
        "PD" => array(
            "DateStart" => "-1 day",
            "DateEnd" => "-1 day",
            "DateFormat" => "Y-m-d H:00",
            "GroupBy" => "h",
        ),
    );

    //==============================================================================
    //      Variables
    //==============================================================================

    /**
     * Widget Parameters Array
     *
     * @var array
     *
     * @ORM\Column(name="Parameters", type="array")
     */
    protected $parameters = array();

    //==============================================================================
    //      Dates Presets Management
    //==============================================================================

    /**
     * Verify Preset Name is Valid
     *
     * @param string $preset Dates Preset Type
     *
     * @return bool
     */
    public static function isPreset(string $preset) : bool
    {
        if (!array_key_exists($preset, static::$presets)) {
            return false;
        }

        return true;
    }

    /**
     * Get Dates Array From Presets
     *
     * @param string $preset Dates Preset Type
     *
     * @return array
     */
    public static function getDatesArray(string $preset = "M") : array
    {
        //==============================================================================
        //  Check If Preset Dates Mode Exists
        if (!self::isPreset($preset)) {
            return array();
        }
        //==============================================================================
        //  Prepare Dates
        $dateStart = new \DateTime(static::$presets[$preset]["DateStart"]);
        $dateStart->setTime(0, 0, 0);
        $dateEnd = new \DateTime(static::$presets[$preset]["DateEnd"]);
        $dateEnd->setTime(23, 59, 59);
        $dateFormat = static::$presets[$preset]["DateFormat"];
        $groupBy = static::$presets[$preset]["GroupBy"];

        //==============================================================================
        //  Return Dates Array
        return  array(
            "DateStart" => $dateStart,
            "DateEnd" => $dateEnd,
            "DateFormat" => $dateFormat,
            "GroupBy" => $groupBy,
        );
    }

    /**
     * Add Dates Array From Presets
     *
     * @param array $parameters with Dates Preset
     *
     * @return array
     */
    public static function addDatesPresets(array $parameters = array()) : array
    {
        if (isset($parameters["DatePreset"]) && self::isPreset($parameters["DatePreset"])) {
            return array_merge($parameters, self::getDatesArray($parameters["DatePreset"]));
        }

        return $parameters;
    }

    /**
     * Set Parameter
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return $this
     */
    public function setParameter(string $key, $value) : self
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
     * @param array $parameters
     *
     * @return $this
     */
    public function setParameters(array $parameters) : self
    {
        $this->parameters = $parameters;

        return $this;
    }

    /**
     * Get Parameters
     *
     * @param bool $withDates Transform Date Preset To Dates
     *
     * @return array
     */
    public function getParameters($withDates = null) : array
    {
        if ($withDates && isset($this->parameters["DatePreset"])) {
            return array_merge($this->parameters, $this->getDatesArray($this->parameters["DatePreset"]));
        }

        return $this->parameters;
    }
}
