<?php

/*
 * This file is part of the Splash Sync project.
 *
 * (c) Bernard Paquier <pro@bernard-paquier.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Splash\Widgets\Entity;

use Doctrine\ORM\Mapping as ORM;

use Splash\Widgets\Models\WidgetCacheBase;

/**
 * @abstract    Splash Widget Cache Entity
 * 
 * @ORM\Entity(repositoryClass="Splash\Widgets\Repository\WidgetCacheRepository")
 * @ORM\Table(name="widgets__cache")
 * @ORM\HasLifecycleCallbacks
 * 
 */
class WidgetCache extends WidgetCacheBase
{
    
    /**
     * @var integer
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    public function getId()
    {
        return $this->id;
    }    
            
}
