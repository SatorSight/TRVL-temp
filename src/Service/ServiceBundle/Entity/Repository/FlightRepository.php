<?php
namespace Service\ServiceBundle\Entity\Repository;

use Service\ServiceBundle\Resources\SUtils;

class FlightRepository extends \Doctrine\ORM\EntityRepository
{
    public function getFlightsByDateAndDir($from, $to, \DateTime $date){
        $query = $this->createQueryBuilder('s')
            ->select('s')
            ->where('s.from = :from')
            ->andWhere('s.to = :to')
            ->andWhere('s.fromDate like :date')
            ->setParameter('from', $from)
            ->setParameter('to', $to)
            ->setParameter('date', $date->format('Y-m-d').'%')
            ->getQuery();
        return  $query->getResult();
    }
}