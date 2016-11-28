<?php
namespace Service\ServiceBundle\Entity\Repository;

class OrderRepository extends \Doctrine\ORM\EntityRepository
{
    public function getOrdersFromTime(\DateTime $time)
    {
        return $this->createQueryBuilder('s')
            ->select('s')
            ->where('s.date > :dateFrom')
            ->addOrderBy('s.date', 'DESC')
            ->setParameter('dateFrom', $time)
            ->getQuery()->getResult();
    }
	
	public function getOrdersFromId($id)
    {
        return $this->createQueryBuilder('s')
            ->select('s')
            ->where('s.id > :idFrom')
            ->addOrderBy('s.id', 'DESC')
            ->setParameter('idFrom', $id)
            ->getQuery()->getResult();
    }
}