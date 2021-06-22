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

namespace Splash\Widgets\Models\Traits;

use DateTime;
use Doctrine\ORM\Mapping                        as ORM;

/**
 * Widget Parameters Trait
 *
 * @author Bernard Paquier <pro@bernard-paquier.fr>
 */
trait ParametersTrait
{
    /**
     * @var array
     */
    public static $presets = array(
        "Y" => array(
            "DateStart" => "first day of january this year",
            "DateEnd" => "last day of december this year",
            "DateFormat" => "Y-m",
            "GroupBy" => "m",
        ),
        "M" => array(
            "DateStart" => "first day of this month",
            "DateEnd" => "midnight first day of next month -1 second",
            "DateFormat" => "Y-m-d",
            "GroupBy" => "d",
        ),
        "W" => array(
            "DateStart" => "midnight last monday",
            "DateEnd" => "midnight next monday -1 second",
            "DateFormat" => "Y-m-d",
            "GroupBy" => "d",
        ),
        "D" => array(
            "DateStart" => "midnight today",
            "DateEnd" => "midnight tomorrow -1 second",
            "DateFormat" => "Y-m-d H:00",
            "GroupBy" => "h",
        ),
        "LM" => array(
            "DateStart" => "midnight today -1 month",
            "DateEnd" => "midnight tomorrow -1 second",
            "DateFormat" => "Y-m-d",
            "GroupBy" => "d",
        ),
        "LW" => array(
            "DateStart" => "midnight today -1 week",
            "DateEnd" => "midnight tomorrow -1 second",
            "DateFormat" => "Y-m-d",
            "GroupBy" => "d",
        ),
        "LY" => array(
            "DateStart" => "midnight first day of this month -1 year",
            "DateEnd" => "midnight first day of next month -1 second",
            "DateFormat" => "Y-m",
            "GroupBy" => "m",
        ),
        "L2W" => array(
            "DateStart" => "midnight today -2 week",
            "DateEnd" => "midnight tomorrow -1 second",
            "DateFormat" => "Y-m-d",
            "GroupBy" => "d",
        ),
        "PY" => array(
            "DateStart" => "midnight first day of january last year",
            "DateEnd" => "midnight first day of january this year -1 second",
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
            "DateStart" => "midnight last week last monday",
            "DateEnd" => "midnight last monday -1 second",
            "DateFormat" => "Y-m-d",
            "GroupBy" => "d",
        ),
        "PD" => array(
            "DateStart" => "midnight yesterday",
            "DateEnd" => "midnight today -1 second",
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
        $presets = static::$presets;
        $dateStart = new DateTime($presets[$preset]["DateStart"]);
        $dateStart->setTime(0, 0, 0);
        $dateEnd = new DateTime($presets[$preset]["DateEnd"]);
        $dateEnd->setTime(23, 59, 59);
        $dateFormat = $presets[$preset]["DateFormat"];
        $groupBy = $presets[$preset]["GroupBy"];

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
