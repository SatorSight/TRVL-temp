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

class GroupFixtures extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $group1 = new Group();
        $group1->setId(1);
        $group1->setName('group1');
        $group1->setPlace($manager->merge($this->getReference('place-1')));
        $group1->setSort(1);
        $manager->persist($group1);

        $group2 = new Group();
        $group2->setId(2);
        $group2->setName('group2');
        $group2->setPlace($manager->merge($this->getReference('place-2')));
        $group2->setSort(2);
        $manager->persist($group2);

        $group3 = new Group();
        $group3->setId(3);
        $group3->setName('group3');
        $group3->setPlace($manager->merge($this->getReference('place-3')));
        $group3->setSort(3);
        $manager->persist($group3);

        $group4 = new Group();
        $group4->setId(4);
        $group4->setName('group4');
        $group4->setPlace($manager->merge($this->getReference('place-4')));
        $group4->setSort(4);
        $manager->persist($group4);

        $group5 = new Group();
        $group5->setId(5);
        $group5->setName('group5');
        $group5->setPlace($manager->merge($this->getReference('place-5')));
        $group5->setSort(5);
        $manager->persist($group5);

        $manager->flush();

        $this->addReference('group-1', $group1);
        $this->addReference('group-2', $group2);
        $this->addReference('group-3', $group3);
        $this->addReference('group-4', $group4);
        $this->addReference('group-5', $group5);
    }

    public function getOrder()
    {
        return 3;
    }

}
