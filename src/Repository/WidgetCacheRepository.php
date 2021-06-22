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

namespace Splash\Widgets\Repository;

use DateTime;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Splash\Widgets\Entity\WidgetCache;

/**
 * @author Bernard Paquier <eshop.bpaquier@gmail.com>
 */
class WidgetCacheRepository extends EntityRepository
{
    /**
     * Retrieve Widget Cache
     *
     * @param string $service       Widget Provider Service Name
     * @param string $type          Widget Type Name
     * @param string $discriminator Widget Discriminator
     *
     * @throws NonUniqueResultException
     *
     * @return null|WidgetCache
     */
    public function findCached(string $service, string $type, string $discriminator) : ?WidgetCache
    {
        return $this->createQueryBuilder("WC")
            ->where("WC.service = :service")
            ->andwhere("WC.type = :type")
            ->andwhere("WC.discriminator = :disc")
            ->andwhere("WC.expireAt > :expire")
            ->setParameter(":service", $service)
            ->setParameter(":type", $type)
            ->setParameter(":disc", $discriminator)
            ->setParameter(":expire", new DateTime())
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /**
     * CleanUp Expired Widgets from Cache
     */
    public function cleanUp() : void
    {
        $this->createQueryBuilder("WC")
            ->delete()
            ->where("WC.expireAt < :expire")
            ->setParameter(":expire", new DateTime())
            ->getQuery()
            ->getResult()
        ;

        $this->getEntityManager()->clear();
    }
}
