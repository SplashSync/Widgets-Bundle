<?php

/*
 *  This file is part of SplashSync Project.
 *
 *  Copyright (C) 2015-2020 Splash Sync  <www.splashsync.com>
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
use Doctrine\ORM\Mapping as ORM;
use Splash\Widgets\Entity\Widget;

/**
 * Widget Cache Access Trait - Define access to a Widget Cached Informations
 *
 * @author Bernard Paquier <pro@bernard-paquier.fr>
 */
trait CacheTrait
{
    /**
     * Widget Human Readable Name
     *
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=250, nullable=true)
     */
    protected $name;

    /**
     * Widget Human Readable Description
     *
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=250, nullable=true)
     */
    protected $description;

    /**
     * Widget Header Title
     *
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=250, nullable=true)
     */
    protected $title;

    /**
     * Widget Header Sub-Title
     *
     * @var string
     *
     * @ORM\Column(name="subtitle", type="string", length=250, nullable=true)
     */
    protected $subtitle;

    /**
     * Widget Header Icon
     *
     * @var string
     *
     * @ORM\Column(name="icon", type="string", length=250, nullable=true)
     */
    protected $icon;

    /**
     * Widget Source/Origin
     *
     * @var string
     *
     * @ORM\Column(name="origin", type="string", length=250, nullable=true)
     */
    protected $origin;

    //==============================================================================
    //      Variables
    //==============================================================================

    /**
     * Widget Discriminator (Identify if refresh is needed)
     *
     * @var string
     *
     * @ORM\Column(name="discriminator", type="string", length=250, nullable=true)
     */
    protected $discriminator;

    /**
     * Widget Cache Contents
     *
     * @var string
     *
     * @ORM\Column(name="contents", type="text")
     */
    protected $contents;

    /**
     * Refreshed Date
     *
     * @var null|DateTime
     *
     * @ORM\Column(name="refreshAt", type="datetime")
     */
    protected $refreshAt;

    /**
     * Cache Expiration Date
     *
     * @var null|DateTime
     *
     * @ORM\Column(name="expireAt", type="datetime")
     */
    protected $expireAt;

    //==============================================================================
    //      Data Operations
    //==============================================================================

    /**
     * Build Widget Disctriminator
     *
     * @param array $options    Widget Options Array
     * @param array $parameters Widget Parameters Array
     *
     * @return string
     */
    public static function buildDiscriminator(array $options, array $parameters) : string
    {
        return (string) md5(serialize($options).serialize($parameters));
    }

    //==============================================================================
    //      Getters & Setters
    //==============================================================================

    /**
     * Set Widget Disctriminator
     *
     * @param string $discriminator
     *
     * @return $this
     */
    public function setDiscriminator($discriminator) : self
    {
        $this->discriminator = $discriminator;

        return $this;
    }

    /**
     * @abstract    Get Widget Disctriminator
     *
     * @return string
     */
    public function getDiscriminator() : string
    {
        return $this->discriminator;
    }

    /**
     * @abstract    Set Widget Cached Contents
     *
     * @param string $contents
     *
     * @return self
     */
    public function setContents($contents) : self
    {
        $this->contents = base64_encode($contents);

        return $this;
    }

    /**
     * @abstract    Get Widget Cached Contents
     *
     * @return string
     */
    public function getContents() : string
    {
        return (string) base64_decode($this->contents, true);
    }

    /**
     * Set refreshAt
     *
     * @param null|DateTime $refreshAt
     *
     * @return $this
     */
    public function setRefreshAt($refreshAt = null) : self
    {
        $this->refreshAt = $refreshAt ? $refreshAt : new DateTime();

        return $this;
    }

    /**
     * Get refreshAt
     *
     * @return null|DateTime
     */
    public function getRefreshAt() : ?DateTime
    {
        return $this->refreshAt;
    }

    /**
     * Set expireAt
     *
     * @param DateTime $expireAt
     *
     * @return $this
     */
    public function setExpireAt(DateTime $expireAt) : self
    {
        $this->expireAt = $expireAt;

        return $this;
    }

    /**
     * Get expireAt
     *
     * @return null|DateTime
     */
    public function getExpireAt() : ?DateTime
    {
        return $this->expireAt;
    }
}
