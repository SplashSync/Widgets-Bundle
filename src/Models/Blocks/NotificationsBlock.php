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

/**
 * Widget Notification Block
 * Render Notifs & Messages as Bootstrap Alerts
 */
class NotificationsBlock extends BaseBlock
{
    //====================================================================//
    // *******************************************************************//
    //  BLOCK GENERICS PARAMETERS
    // *******************************************************************//
    //====================================================================//

    //====================================================================//
    // Define Standard Data Fields for this Widget Block
    public static $DATA = array(
        'error' => null,
        'warning' => null,
        'info' => null,
        'success' => null,
    );

    /**
     * @var string
     */
    protected $type = "NotificationsBlock";

    //====================================================================//
    // *******************************************************************//
    //  Block Getter & Setter Functions
    // *******************************************************************//
    //====================================================================//

    /**
     * Set Error
     *
     * @param string $text
     *
     * @return $this
     */
    public function setError(string $text) : self
    {
        $this->data["error"] = $text;

        return $this;
    }

    /**
     * Get Error
     *
     * @return null|string
     */
    public function getError() : ?string
    {
        return $this->data["error"];
    }

    /**
     * Set Warning
     *
     * @param string $text
     *
     * @return $this
     */
    public function setWarning(string $text) : self
    {
        $this->data["warning"] = $text;

        return $this;
    }

    /**
     * Get Warning
     *
     * @return null|string
     */
    public function getWarning() : ?string
    {
        return $this->data["warning"];
    }

    /**
     * Set Info
     *
     * @param string $text
     *
     * @return $this
     */
    public function setInfo(string $text) : self
    {
        $this->data["info"] = $text;

        return $this;
    }

    /**
     * Get Info
     *
     * @return null|string
     */
    public function getInfo() : ?string
    {
        return $this->data["info"];
    }

    /**
     * Set Success
     *
     * @param string $text
     *
     * @return $this
     */
    public function setSuccess(string $text) : self
    {
        $this->data["success"] = $text;

        return $this;
    }

    /**
     * Get Success
     *
     * @return null|string
     */
    public function getSuccess() : ?string
    {
        return $this->data["success"];
    }

    /**
     * Set Block Contents
     *
     * @param null|array|ArrayObject $contents
     *
     * @return $this
     */
    public function setContents($contents) : self
    {
        //==============================================================================
        //  Safety Check
        if (!is_array($contents) && !($contents instanceof ArrayObject)) {
            return $this;
        }
        //==============================================================================
        //  Import Error
        if (!empty($contents["error"])) {
            $this->setError($contents["error"]);
        }
        //==============================================================================
        //  Import Warning
        if (!empty($contents["warning"])) {
            $this->setWarning($contents["warning"]);
        }
        //==============================================================================
        //  Import Info
        if (!empty($contents["info"])) {
            $this->setInfo($contents["info"]);
        }
        //==============================================================================
        //  Import Success
        if (!empty($contents["success"])) {
            $this->setSuccess($contents["success"]);
        }

        return $this;
    }
}
