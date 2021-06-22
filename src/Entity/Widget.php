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

namespace Splash\Widgets\Entity;

use Doctrine\ORM\Mapping as ORM;
use Splash\Widgets\Models\WidgetBase;

/**
 * Splash Widget Entity
 *
 * @ORM\Entity()
 * @ORM\Table(name="widgets__widget")
 * @ORM\HasLifecycleCallbacks
 */
class Widget extends WidgetBase
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

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
