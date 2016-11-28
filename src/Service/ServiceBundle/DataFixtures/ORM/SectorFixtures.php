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

class SectorFixtures extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $sector1 = new Sector();
        $sector1->setId(1);
        $sector1->setName('sector1');
        $sector1->setPlace($manager->merge($this->getReference('place-1')));
        $manager->persist($sector1);

        $sector2 = new Sector();
        $sector2->setId(2);
        $sector2->setName('sector2');
        $sector2->setPlace($manager->merge($this->getReference('place-2')));
        $manager->persist($sector2);

        $sector3 = new Sector();
        $sector3->setId(3);
        $sector3->setName('sector3');
        $sector3->setPlace($manager->merge($this->getReference('place-3')));
        $manager->persist($sector3);

        $sector4 = new Sector();
        $sector4->setId(4);
        $sector4->setName('sector4');
        $sector4->setPlace($manager->merge($this->getReference('place-4')));
        $manager->persist($sector4);

        $sector5 = new Sector();
        $sector5->setId(5);
        $sector5->setName('sector5');
        $sector5->setPlace($manager->merge($this->getReference('place-5')));
        $manager->persist($sector1);



        $manager->flush();
    }

    public function getOrder()
    {
        return 10;
    }

}
