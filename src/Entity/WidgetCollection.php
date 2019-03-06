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

namespace Splash\Widgets\Entity;

use Doctrine\ORM\Mapping                        as ORM;
use Splash\Widgets\Models\WidgetCollectionBase;

/**
 * Widgets Collection Object
 *
 * @ORM\Entity()
 * @ORM\Table(name="widgets__collection")
 * @ORM\HasLifecycleCallbacks
 */
class WidgetCollection extends WidgetCollectionBase
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * Get Entity Id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}
