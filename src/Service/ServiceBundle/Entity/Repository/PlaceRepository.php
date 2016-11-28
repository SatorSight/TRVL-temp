<?php
namespace Service\ServiceBundle\Entity\Repository;

class PlaceRepository extends \Doctrine\ORM\EntityRepository
{
    public function getPlacesSorted()
    {
        return $this->createQueryBuilder('s')
            ->select('s')
            ->addOrderBy('s.sort', 'ASC')
            ->getQuery()->getResult();
    }
}