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

use ArrayObject;
use DateTime;
use Doctrine\ORM\Mapping                        as ORM;
use Exception;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Widget Options Trait
 *
 * @author Bernard Paquier <pro@bernard-paquier.fr>
 */
trait OptionsTrait
{
    //==============================================================================
    //      Constants
    //==============================================================================

    //==============================================================================
    //      Widgets Width
    /** @var string */
    public static $widthXs = "col-sm-6 col-md-4 col-lg-3";
    /** @var string */
    public static $widthSm = "col-sm-6 col-md-6 col-lg-4";
    /** @var string */
    public static $widthDefault = "col-sm-12 col-md-6 col-lg-6";
    /** @var string */
    public static $widthM = "col-sm-12 col-md-6 col-lg-6";
    /** @var string */
    public static $widthL = "col-sm-12 col-md-6 col-lg-8";
    /** @var string */
    public static $widthXl = "col-sm-12 col-md-12 col-lg-12";

    //==============================================================================
    //      Widgets Color
    /** @var string */
    public static $colorNone = " ";
    /** @var string */
    public static $colorDefault = "default";
    /** @var string */
    public static $colorPrimary = "primary";
    /** @var string */
    public static $colorSuccess = "success";
    /** @var string */
    /** @var string */
    public static $colorInfo = "info";
    /** @var string */
    public static $colorWarning = "warning";
    /** @var string */
    public static $colorDanger = "danger";

    //==============================================================================
    //      Rendering Mode
    /** @var string */
    public static $modeDefault = "bs4";
    /** @var string */
    public static $modeBootstrap3 = "bs3";
    /** @var string */
    public static $modeBootstrap4 = "bs4";

    //==============================================================================
    //      Variables
    //==============================================================================

    /**
     * Widget Options Array
     *
     * @var array
     *
     * @ORM\Column(name="Options", type="array")
     */
    protected $options;

    //==============================================================================
    //      Data Operations
    //==============================================================================

    /**
     * Set Width
     *
     * @param string $width Widget Width Code
     *
     * @return $this
     */
    public function setWidth(string $width) : self
    {
        if (empty($width)) {
            $this->options["Width"] = static::$widthDefault;

            return $this;
        }

        switch ($width) {
            case "xs":
                $this->options["Width"] = static::$widthXs;

                break;
            case "sm":
                $this->options["Width"] = static::$widthSm;

                break;
            case "m":
                $this->options["Width"] = static::$widthM;

                break;
            case "l":
                $this->options["Width"] = static::$widthL;

                break;
            case "xl":
                $this->options["Width"] = static::$widthXl;

                break;
            default:
                $this->options["Width"] = $width;

                break;
        }

        return $this;
    }

    /**
     * Set Header Status
     *
     * @param bool $state
     *
     * @return $this
     */
    public function setHeader(bool $state = null) : self
    {
        $this->options["Header"] = is_null($state) ? true : $state;

        return $this;
    }

    /**
     * Set Footer Status
     *
     * @param bool $state
     *
     * @return $this
     */
    public function setFooter(bool $state = null) : self
    {
        $this->options["Footer"] = is_null($state) ? true : $state;

        return $this;
    }

    /**
     * Get Widget Max Cache Date
     *
     * @throws Exception
     *
     * @return DateTime
     */
    public function getCacheMaxDate() : DateTime
    {
        if (!isset($this->options['UseCache'])
            || !isset($this->options['CacheLifeTime'])
            || !$this->options['UseCache']) {
            return new DateTime();
        }

        return new DateTime($this->options['CacheLifeTime']."minutes");
    }

    //==============================================================================
    //      Getters & Setters
    //==============================================================================

    /**
     * Set Widget Options
     *
     * @param array|ArrayObject $options User Defined Options
     *
     * @return $this
     */
    public function setOptions($options = null) : self
    {
        //==============================================================================
        //  Update Options Array using OptionResolver
        $this->options = $this->validateOptions($options);

        return $this;
    }

    /**
     * Get Widget Options
     *
     * @return Array
     */
    public function getOptions() : array
    {
        return $this->validateOptions($this->options);
    }

    /**
     * Validate Widget Options
     *
     * @param array|ArrayObject $options User Defined Options
     *
     * @return array
     */
    public function validateOptions($options = null) : array
    {
        //==============================================================================
        //  Check Options is ArrayObject
        if ($options instanceof ArrayObject) {
            $options = $options->getArrayCopy();
        }
        //==============================================================================
        //  Check Options Array not Empty or Not an Array
        if (empty($options) || !is_array($options)) {
            return  $this->getDefaultOptions();
        }
        //==============================================================================
        //  Init Options Array using OptionResolver
        $resolver = new OptionsResolver();
        //==============================================================================
        //  Configure OptionResolver
        $resolver->setDefaults($this->getDefaultOptions());
        //==============================================================================
        //  Update Options Array using OptionResolver
        try {
            return  $resolver->resolve($options);
        } catch (UndefinedOptionsException $ex) {
            $this->getDefaultOptions();
        } catch (InvalidOptionsException $ex) {
            $this->getDefaultOptions();
        }

        return $this->getDefaultOptions();
    }

    /**
     * Update Widget Options With Given Values
     *
     * @param array $options User Defined Options
     *
     * @return $this
     */
    public function mergeOptions(array $options = array()) : self
    {
        return $this->setOptions(array_replace_recursive($this->getOptions(), $options));
    }

    /**
     * Get Widget Defaults Options
     *
     * @return array
     */
    public static function getDefaultOptions() : array
    {
        return array(
            'Width' => static::$widthDefault,
            'Color' => static::$colorDefault,
            'Mode' => static::$modeDefault,
            'Header' => true,
            'Footer' => true,
            'Border' => true,
            'DatePreset' => "M",
            'UseCache' => true,
            'CacheLifeTime' => 120,
            'Editable' => false,
            'EditMode' => false,
        );
    }
}
