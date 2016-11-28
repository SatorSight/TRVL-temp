<?php
namespace Service\ServiceBundle\Entity\Repository;

class GroupRepository extends \Doctrine\ORM\EntityRepository
{
    public function getGroupsSorted()
    {
        return $this->createQueryBuilder('s')
            ->select('s')
            ->addOrderBy('s.place', 'ASC')
            ->addOrderBy('s.sort', 'ASC')
            ->getQuery()->getResult();
    }
}