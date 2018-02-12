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

use Splash\Widgets\Entity\Widget;

use Splash\Widgets\Models\Traits\AccessTrait;
use Splash\Widgets\Models\Traits\CacheTrait;
use Splash\Widgets\Models\Traits\DefinitionTrait;
use Splash\Widgets\Models\Traits\OptionsTrait;
use Splash\Widgets\Models\Traits\ParametersTrait;

/**
 * Widget Contents Cache Model 
 * 
 * @author Bernard Paquier <pro@bernard-paquier.fr>
 */
class WidgetCacheBase
{
    
    use AccessTrait;
    use CacheTrait;
    use DefinitionTrait;
    use OptionsTrait;
    use ParametersTrait;
    
    public function __construct(Widget $Widget = Null)
    {
        if (!$Widget){
            return $this;
        } 
        
        $this->setDefinition($Widget);
        
        return $this;
    }   
    
    public function setDefinition(Widget $Widget) {
        
        $this
            ->setService($Widget->getService())
            ->setType($Widget->getType())
            ->setName($Widget->getName())
            ->setDescription($Widget->getName())
            ->setTitle($Widget->getTitle())
            ->setSubTitle($Widget->getSubTitle())
            ->setIcon($Widget->getIcon())
            ->setOrigin($Widget->getOrigin())
            ->setOptions($Widget->getOptions())
            ;
                
        return $this;                
    }

}
