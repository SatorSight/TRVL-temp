<?php

namespace Service\ServiceBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Service\ServiceBundle\Entity\Order;
use Service\ServiceBundle\Entity\OrderContent;
use Service\ServiceBundle\Entity\Position;
use Service\ServiceBundle\Entity\Group;
use Service\ServiceBundle\Entity\Place;
use Service\ServiceBundle\Entity\Sector;

class PlaceFixtures extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $place1 = new Place();
        $place1->setId(1);
        $place1->setName('place1');
        $manager->persist($place1);

        $place2 = new Place();
        $place2->setId(2);
        $place2->setName('place2');
        $manager->persist($place2);

        $place3 = new Place();
        $place3->setId(3);
        $place3->setName('place3');
        $manager->persist($place3);

        $place4 = new Place();
        $place4->setId(4);
        $place4->setName('place4');
        $manager->persist($place4);

        $place5 = new Place();
        $place5->setId(5);
        $place5->setName('place5');
        $manager->persist($place5);
        
        $manager->flush();

        $this->addReference('place-1', $place1);
        $this->addReference('place-2', $place2);
        $this->addReference('place-3', $place3);
        $this->addReference('place-4', $place4);
        $this->addReference('place-5', $place5);
    }

    public function getOrder()
    {
        // the order in which fixtures will be loaded
        // the lower the number, the sooner that this fixture is loaded
        return 1;
    }

}
