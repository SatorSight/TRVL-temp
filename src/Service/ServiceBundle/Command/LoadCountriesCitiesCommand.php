<?php
namespace Service\ServiceBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

use Doctrine\ORM\EntityManager;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Service\ServiceBundle\Entity\Country;
use Service\ServiceBundle\Entity\City;

class LoadCountriesCitiesCommand extends ContainerAwareCommand
{
    /**
     * Refreshing cities and countries from support.travelpayouts.com
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output){

        /** @var EntityManager $em */
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        $all_countries =  $em->getRepository('ServiceServiceBundle:Country')->findAll();
        foreach($all_countries as $co)
            $em->remove($co);

        $all_cities =  $em->getRepository('ServiceServiceBundle:City')->findAll();
        foreach($all_cities as $ci)
            $em->remove($ci);

        $em->flush();

        $country_json = file_get_contents('http://api.travelpayouts.com/data/countries.json');
        $countries = json_decode($country_json);

        $cities_json = file_get_contents('http://api.travelpayouts.com/data/cities.json');
        $cities = json_decode($cities_json);

        $countriesInserted = 0;
        $citiesInserted = 0;

        $citiesOrganized = [];
        foreach($cities as $key => $c)
            $citiesOrganized[$c->country_code][] = $c;

        foreach($countries as $key => $countryObj){

            $country = new Country();
            $country->setCode($countryObj->code);
            $country->setName($countryObj->name);
            $country->setRuName($countryObj->name_translations->ru);

            $countriesInserted++;
            $em->persist($country);

            if(!empty($citiesOrganized[$countryObj->code]))
                foreach($citiesOrganized[$countryObj->code] as $key2 => $cityObj){

                    if(!empty($cityObj->name_translations->ru)){

                        $newCity = new City();
                        $newCity->setName($cityObj->name);
                        $newCity->setCode($cityObj->code);
                        $newCity->setCountry($country);
                        $newCity->setCountryCode($cityObj->country_code);
                        $newCity->setNameRu($cityObj->name_translations->ru);

                        $citiesInserted++;
                        $em->persist($newCity);
                    }
                }
        }

        $em->flush();

        $output->writeln('Countries inserted: '.$countriesInserted);
        $output->writeln('Cities inserted: '.$citiesInserted);
        $output->writeln('Load countries cities command end');
    }

    protected function configure(){
        $this->setName('app:load-places')
             ->setDescription('Loads countries and cities with IATA codes.');
    }
}