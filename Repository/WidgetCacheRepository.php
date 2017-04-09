<?php

namespace Splash\Widgets\Repository;

use Doctrine\ORM\EntityRepository;

use Splash\Widgets\Entity\Widget;

/**
 * @author Bernard Paquier <eshop.bpaquier@gmail.com>
 */
class WidgetCacheRepository extends EntityRepository
{
    /**
     * Retrieve Widget Cache
     *
     * @param   string      $Service        Widget Provider Service Name
     * @param   string      $Type           Widget Type Name     
     *
     * @return array|null
     */
    public function findCached(string $Service, string $Type)
    {
        return $this->createQueryBuilder("WC")
                    ->where("WC.service = :service")
                    ->andwhere("WC.type = :type")
                    ->andwhere("WC.expireAt > :expire")
                    ->setParameter(":service",  $Service)
                    ->setParameter(":type",     $Type)
                    ->setParameter(":expire",   new \DateTime())
                    ->getQuery()
                    ->getOneOrNullResult();
                ;
    }
}
