<?php

namespace Splash\Widgets\Entity;

use Doctrine\ORM\Mapping                        as ORM;

use Splash\Widgets\Models\WidgetCollectionBase;

/**
 * @abstract    Widgets Collection Object
 *
 * @ORM\Entity()
 * @ORM\Table(name="widgets__collection")
 * @ORM\HasLifecycleCallbacks
 * 
 */
class WidgetCollection extends WidgetCollectionBase
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

}
