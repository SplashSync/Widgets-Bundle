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

use Doctrine\ORM\Mapping as ORM;

/**
 * @abstract Widget Access Trait - Defin access to a Widget 
 * 
 * @author Bernard Paquier <pro@bernard-paquier.fr>
 */
trait CacheTrait
{
    
    /**
     * @abstract    Widget Discriminator (Identify if refresh is needed)
     * @var         string
     * @ORM\Column(name="discriminator", type="string", length=250)
     */
    protected $discriminator   =   Null;
    
    /**
     * @var \DateTime
     * @ORM\Column(name="refreshedAt", type="datetime", nullable=TRUE)
     */
    private $refreshedAt       =    Null;
       
}
