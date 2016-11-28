<?php
namespace Service\ServiceBundle\Entity\Repository;

use Service\ServiceBundle\Entity\Position;

class PositionRepository extends \Doctrine\ORM\EntityRepository
{
    public function getPositionsSorted()
    {
		
		
		$res = $this->createQueryBuilder('p')
				->add('select', 'p')
				->add('from', 'Service\ServiceBundle\Entity\Position p, Service\ServiceBundle\Entity\Group g')
				->add('where', 'p.group = g.id')
				->add('orderBy', 'g.sort ASC, p.sort ASC')
				//->add('orderBy', 'p.group ASC')
				//->add('orderBy', '')
				
				->add('groupBy', 'p.id')

				->getQuery()->getResult();
		//$q = $res->getSQLQuery();
		
				
        return $res;
			
			
			
			
			
		/*return $this->createQueryBuilder('p')
            ->select('p')
            ->addOrderBy('p.group', 'ASC')
            ->addOrderBy('p.sort', 'ASC')
            ->getQuery()->getResult();*/
    }
}