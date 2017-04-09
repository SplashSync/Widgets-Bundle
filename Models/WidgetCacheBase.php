<?php

/*
 * This file is part of the Splash Sync project.
 *
 * (c) Bernard Paquier <pro@bernard-paquier.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Splash\Widgets\Models;

use Doctrine\Common\Collections\ArrayCollection;

use Splash\Widgets\Models\Traits\AccessTrait;
use Splash\Widgets\Models\Traits\CacheTrait;

/**
 * Widget Contents Cache Model 
 * 
 * @author Bernard Paquier <pro@bernard-paquier.fr>
 */
class WidgetCacheBase
{
    
    use AccessTrait;
    use CacheTrait;
    
    
    public function __construct()
    {
    }    

}
