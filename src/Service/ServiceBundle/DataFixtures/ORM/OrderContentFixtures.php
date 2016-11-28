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

class OrderContentFixtures extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $orderContent1 = new OrderContent();
        $orderContent1->setAmount(1);
        $orderContent1->setSum(1);
        $orderContent1->setOrder($manager->merge($this->getReference('order-1')));
        $orderContent1->setPosition($manager->merge($this->getReference('position-1')));
        $manager->persist($orderContent1);

        $orderContent2 = new OrderContent();
        $orderContent2->setAmount(2);
        $orderContent2->setSum(2);
        $orderContent2->setOrder($manager->merge($this->getReference('order-2')));
        $orderContent2->setPosition($manager->merge($this->getReference('position-2')));
        $manager->persist($orderContent2);

        $orderContent3 = new OrderContent();
        $orderContent3->setAmount(3);
        $orderContent3->setSum(3);
        $orderContent3->setOrder($manager->merge($this->getReference('order-3')));
        $orderContent3->setPosition($manager->merge($this->getReference('position-3')));
        $manager->persist($orderContent3);

        $orderContent4 = new OrderContent();
        $orderContent4->setAmount(4);
        $orderContent4->setSum(4);
        $orderContent4->setOrder($manager->merge($this->getReference('order-4')));
        $orderContent4->setPosition($manager->merge($this->getReference('position-4')));
        $manager->persist($orderContent4);

        $orderContent5 = new OrderContent();
        $orderContent5->setAmount(5);
        $orderContent5->setSum(5);
        $orderContent5->setOrder($manager->merge($this->getReference('order-5')));
        $orderContent5->setPosition($manager->merge($this->getReference('position-5')));
        $manager->persist($orderContent5);



        $manager->flush();
    }

    public function getOrder()
    {
        return 11;
    }

}
