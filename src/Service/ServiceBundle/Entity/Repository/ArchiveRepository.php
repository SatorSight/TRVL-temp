<?php
namespace Service\ServiceBundle\Entity\Repository;

class ArchiveRepository extends \Doctrine\ORM\EntityRepository
{
    public function getArchiveByDay(\DateTime $time)
    {
        return $this->createQueryBuilder('a')
            ->select('a')
            ->where('DATE(a.date) = DATE_SUB(:date_day, INTERVAL 1 DAY)')
            ->addOrderBy('a.date', 'DESC')
            ->setParameter('date_day', $time)
            ->getQuery()->getSQL();
//        return $this->createQueryBuilder('a')
//            ->select('a')
//            ->where('DATE(a.date) = DATE_SUB(:date_day, INTERVAL 1 DAY)')
//            ->addOrderBy('a.date', 'DESC')
//            ->setParameter('date_day', $time)
//            ->getQuery()->getResult();
    }
}