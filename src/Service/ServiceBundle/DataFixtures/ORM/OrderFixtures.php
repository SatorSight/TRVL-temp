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
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\DateTime;

class OrderFixtures extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $order1 = new Order();
        $order1->setId(1);
        $order1->setDate(new \DateTime('2011-07-23 06:22:53'));
        $order1->setPlace('place1');
        $order1->setWorkday($manager->merge($this->getReference('workday-1')));
        $manager->persist($order1);

        $order2 = new Order();
        $order2->setId(2);
        $order2->setDate(new \DateTime('2011-07-23 06:22:53'));
        $order2->setPlace('place2');
        $order2->setWorkday($manager->merge($this->getReference('workday-1')));
        $manager->persist($order2);

        $order3 = new Order();
        $order3->setId(3);
        $order3->setDate(new \DateTime('2011-07-23 06:22:53'));
        $order3->setPlace('place3');
        $order3->setWorkday($manager->merge($this->getReference('workday-1')));
        $manager->persist($order3);

        $order4 = new Order();
        $order4->setId(4);
        $order4->setDate(new \DateTime('2011-07-23 06:22:53'));
        $order4->setPlace('place4');
        $order4->setWorkday($manager->merge($this->getReference('workday-2')));
        $manager->persist($order4);

        $order5 = new Order();
        $order5->setId(5);
        $order5->setDate(new \DateTime('2011-07-23 06:22:53'));
        $order5->setPlace('place5');
        $order5->setWorkday($manager->merge($this->getReference('workday-2')));
        $manager->persist($order5);

        $manager->flush();

        $this->addReference('order-1', $order1);
        $this->addReference('order-2', $order2);
        $this->addReference('order-3', $order3);
        $this->addReference('order-4', $order4);
        $this->addReference('order-5', $order5);
    }

    public function getOrder()
    {
        // the order in which fixtures will be loaded
        // the lower the number, the sooner that this fixture is loaded
        return 2;
    }

}
