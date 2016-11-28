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
use Service\ServiceBundle\Entity\WorkDay;

class WorkDayFixtures extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $workday1 = new WorkDay();
        $workday1->setId(1);
        $workday1->setOpened(new \DateTime('2011-07-23 06:22:53'));
        $workday1->setClosed(new \DateTime('2011-07-24 06:22:53'));
        $manager->persist($workday1);

        $workday2 = new WorkDay();
        $workday2->setId(2);
        $workday2->setOpened(new \DateTime('2011-07-25 06:22:53'));
        $workday2->setClosed(new \DateTime('2011-07-26 06:22:53'));
        $manager->persist($workday2);

        $manager->flush();

        $this->addReference('workday-1', $workday1);
        $this->addReference('workday-2', $workday2);
    }

    public function getOrder()
    {
        return 1;
    }

}
