<?php

namespace Service\ServiceBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Service\ServiceBundle\Entity\Order;
use Service\ServiceBundle\Entity\OrderContent;
use Service\ServiceBundle\Entity\Position;
use Service\ServiceBundle\Entity\Group;
use Service\ServiceBundle\Entity\Place;
use Service\ServiceBundle\Entity\Sector;

class PositionFixtures extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $position1 = new Position();
        $position1->setId(1);
        $position1->setName('position1');
        $position1->setDescription('description1');
        $position1->setPrice('1');
        $position1->setUrl('1');
        $position1->setImage('1');
        $position1->setSort(1);
        $position1->setGroup($manager->merge($this->getReference('group-1')));
        $manager->persist($position1);

        $position2 = new Position();
        $position2->setId(2);
        $position2->setName('position2');
        $position2->setDescription('description2');
        $position2->setPrice('2');
        $position2->setUrl('2');
        $position2->setImage('2');
        $position2->setSort(2);
        $position2->setGroup($manager->merge($this->getReference('group-2')));
        $manager->persist($position2);

        $position3 = new Position();
        $position3->setId(3);
        $position3->setName('position3');
        $position3->setDescription('description3');
        $position3->setPrice('3');
        $position3->setUrl('3');
        $position3->setImage('3');
        $position3->setSort(3);
        $position3->setGroup($manager->merge($this->getReference('group-3')));
        $manager->persist($position3);

        $position4 = new Position();
        $position4->setId(4);
        $position4->setName('position4');
        $position4->setDescription('description4');
        $position4->setPrice('4');
        $position4->setUrl('4');
        $position4->setImage('4');
        $position4->setSort(4);
        $position4->setGroup($manager->merge($this->getReference('group-4')));
        $manager->persist($position4);

        $position5 = new Position();
        $position5->setId(5);
        $position5->setName('position5');
        $position5->setDescription('description5');
        $position5->setPrice('5');
        $position5->setUrl('5');
        $position5->setImage('5');
        $position5->setSort(5);
        $position5->setGroup($manager->merge($this->getReference('group-5')));
        $manager->persist($position5);

        $manager->flush();

        $this->addReference('position-1', $position1);
        $this->addReference('position-2', $position2);
        $this->addReference('position-3', $position3);
        $this->addReference('position-4', $position4);
        $this->addReference('position-5', $position5);
    }

    public function getOrder()
    {
        return 10;
    }

}
