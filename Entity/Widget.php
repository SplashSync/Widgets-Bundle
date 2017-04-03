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

use Splash\Widgets\Models\WidgetBase;

/**
 * @abstract    Splash Widget Entity
 * 
 * @ORM\Entity()
 * @ORM\Table(name="widgets__widget")
 * @ORM\HasLifecycleCallbacks
 * 
 */
class Widget extends WidgetBase
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
