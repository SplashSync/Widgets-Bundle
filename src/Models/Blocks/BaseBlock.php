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

namespace Splash\Widgets\Models\Blocks;

use ArrayObject;
use Splash\Widgets\Services\FactoryService;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Widget Content Model
 *
 * @author Bernard Paquier <pro@bernard-paquier.fr>
 */
class BaseBlock
{
    //====================================================================//
    // *******************************************************************//
    //  BLOCK GENERICS PARAMETERS
    // *******************************************************************//
    //====================================================================//

    const SIZE_XS = "col-sm-6 col-md-4 col-lg-3";
    const SIZE_SM = "col-sm-6 col-md-6 col-lg-4";
    const SIZE_DEFAULT = "col-sm-12 col-md-12 col-lg-12";
    const SIZE_M = "col-sm-12 col-md-6 col-lg-6";
    const SIZE_L = "col-sm-12 col-md-6 col-lg-8";
    const SIZE_XL = "col-sm-12 col-md-12 col-lg-12";

    //====================================================================//
    // Define Standard Data Fields for this Widget Block
    public static $DATA = array(
    );

    //====================================================================//
    // Define Standard Options for this Widget Block
    // Uncomment to override défault options
    public static $OPTIONS = array(
        'Width' => self::SIZE_DEFAULT,
    );

    /**
     * @var FactoryService
     */
    public $parent;

    //====================================================================//
    // *******************************************************************//
    //  Variables Definition
    // *******************************************************************//
    //====================================================================//

    /**
     * @var string
     */
    protected $type;

    /**
     * @var array
     */
    protected $options;

    /**
     * @var array
     */
    protected $data;

    /**
     * Class Constructor
     */
    public function __construct()
    {
        $this->data = static::$DATA;
        $this->options = static::$OPTIONS;
    }

    /**
     * Magic Widget Getter Function
     *
     * @param string $name Function Name
     * @param array  $args Function Arguments
     */
    public function __set($name, $args)
    {
        if (method_exists($this, $name)) {
            switch (count($args)) {
                case 0:
                    $this->{$name}();

                    break;
                case 1:
                    $this->{$name}($args[0]);

                    break;
                case 2:
                    $this->{$name}($args[0], $args[1]);

                    break;
            }
        }

//        return $this;
    }

    //====================================================================//
    // *******************************************************************//
    //  Widget Getter & Setter Functions
    // *******************************************************************//
    //====================================================================//

    /**
     * Get Data
     *
     * @return string
     */
    public function getType() : string
    {
        return $this->type;
    }

    /**
     * Set Data
     *
     * @param array|ArrayObject $data
     *
     * @return $this
     */
    public function setData($data) : self
    {
        //==============================================================================
        //  Safety Check
        if ($data instanceof ArrayObject) {
            $data = $data->getArrayCopy();
        }
        //==============================================================================
        //  Check Data Array not Empty or Not an Array
        if (empty($data) || !is_array($data)) {
            $this->data = static::$DATA;

            return $this;
        }
        //==============================================================================
        //  Init Data Array using OptionResolver
        $resolver = new OptionsResolver();
        //==============================================================================
        //  Configure OptionResolver
        $resolver->setDefaults(static::$DATA);
        //==============================================================================
        //  Update Options Array using OptionResolver
        try {
            $this->data = $resolver->resolve($data);
        } catch (UndefinedOptionsException $ex) {
            $this->data = static::$DATA;
        } catch (InvalidOptionsException $ex) {
            $this->data = static::$DATA;
        }

        return $this;
    }

    /**
     * Get Data
     *
     * @return Array
     */
    public function getData() : array
    {
        return $this->data;
    }

    /**
     * Set Widget Contents Block Options
     *
     * @param null|array|ArrayObject $options User Defined Options
     *
     * @return self
     */
    public function setOptions($options = null) : self
    {
        //==============================================================================
        //  Safety Check
        if ($options instanceof ArrayObject) {
            $options = $options->getArrayCopy();
        }
        //==============================================================================
        //  Check Options Array not Empty or Not an Array
        if (empty($options) || !is_array($options)) {
            $this->options = static::$OPTIONS;

            return $this;
        }
        //==============================================================================
        //  Init Options Array using OptionResolver
        $resolver = new OptionsResolver();
        //==============================================================================
        //  Configure OptionResolver
        $resolver->setDefaults(static::$OPTIONS);
        //==============================================================================
        //  Update Options Array using OptionResolver
        try {
            $this->options = $resolver->resolve($options);
        } catch (UndefinedOptionsException $ex) {
            $this->options = static::$OPTIONS;
        } catch (InvalidOptionsException $ex) {
            $this->options = static::$OPTIONS;
        }

        return $this;
    }

    /**
     * Get Widget Contents Block Options
     *
     * @return Array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Set Width
     *
     * @param string $width
     *
     * @return $this
     */
    public function setWidth(string $width = self::SIZE_DEFAULT) : self
    {
        switch ($width) {
            case "xs":
                $this->options["Width"] = self::SIZE_XS;

                break;
            case "sm":
                $this->options["Width"] = self::SIZE_SM;

                break;
            case "m":
                $this->options["Width"] = self::SIZE_M;

                break;
            case "l":
                $this->options["Width"] = self::SIZE_L;

                break;
            case "xl":
                $this->options["Width"] = self::SIZE_XL;

                break;
            default:
                $this->options["Width"] = $width;

                break;
        }

        return $this;
    }

    /**
     * Check if is Block Data is Empty
     *
     * @return Bool
     */
    public function isEmpty() : bool
    {
        return !empty($this->data);
    }

    /**
     * @return FactoryService
     */
    public function end() : FactoryService
    {
        return $this->parent;
    }
}
