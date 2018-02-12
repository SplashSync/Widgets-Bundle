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
     * @param   string      $Discriminator  Widget Discriminator     
     *
     * @return array|null
     */
    public function findCached(string $Service, string $Type, string $Discriminator)
    {
        return $this->createQueryBuilder("WC")
                    ->where("WC.service = :service")
                    ->andwhere("WC.type = :type")
                    ->andwhere("WC.discriminator = :disc")
                    ->andwhere("WC.expireAt > :expire")
                    ->setParameter(":service",  $Service)
                    ->setParameter(":type",     $Type)
                    ->setParameter(":disc",     $Discriminator)
                    ->setParameter(":expire",   new \DateTime())
                    ->getQuery()
                    ->getOneOrNullResult()
                ;
    }
    
    /**
     * CleanUp Expired Widgets from Cache
     *
     * @return void
     */
    public function cleanUp()
    {
        $this->createQueryBuilder("WC")
                    ->delete()
                    ->where("WC.expireAt < :expire")
                    ->setParameter(":expire",   new \DateTime())
                    ->getQuery()
                    ->getResult()
                ;
        
        $this->getEntityManager()->clear();
    }    
}
