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

use Doctrine\ORM\Mapping as ORM;

/**
 * Widget Access Trait - Define access to a Widget
 */
trait AccessTrait
{
    /**
     * Widget Provider Service Name
     *
     * @var string
     *
     * @ORM\Column(name="service", type="string", length=250)
     */
    protected $service;

    /**
     * Widget Type Name
     *
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=250)
     */
    protected $type;

    /**
     * Widget Definition Extras
     *
     * @var array
     *
     * @ORM\Column(name="Settings", type="array")
     */
    protected $extras = array();

    //====================================================================//
    // *******************************************************************//
    //  Widget Getter & Setter Functions
    // *******************************************************************//
    //====================================================================//

    /**
     * Set Service Name
     *
     * @param string $service
     *
     * @return $this
     */
    public function setService(string $service) : self
    {
        $this->service = $service;

        return $this;
    }

    /**
     * Get Service Name
     *
     * @return string
     */
    public function getService() : string
    {
        return strtolower($this->service);
    }

    /**
     * Set Widget Type
     *
     * @param string $type
     *
     * @return $this
     */
    public function setType($type) : self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get Widget Type
     *
     * @return string
     */
    public function getType() : string
    {
        return (string) $this->type;
    }

    /**
     * Set Widget Extra Parameters
     *
     * @param array $extras
     *
     * @return $this
     */
    public function setExtras(array $extras) : self
    {
        $this->extras = $extras;

        return $this;
    }

    /**
     * Get Widget Extra Parameters
     *
     * @return array
     */
    public function getExtras() : array
    {
        return $this->extras;
    }
}
