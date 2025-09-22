<?php

namespace App\DataFixtures;

use App\Entity\Carnet;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CarnetFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {   
        $faker = Factory::create('fr_BE');
        $modeleTitres = ["Sur la route de ",
                        "Souvenirs de ",
                        "Escapade en ",
                        "Chroniques de ",
                        "À la découverte de ",];
        
        for ($i=1; $i<=12; $i++)
        {
            $lieu = $faker->boolean() ? $faker->unique()->city() : $faker->unique()->country();
            $photo = "https://picsum.photos/800/600?random=" . rand(1, 200);

            $carnet = new Carnet();
            $carnet->setTitre($modeleTitres[rand(0,count($modeleTitres)-1)] . $lieu)
                   ->setDateCarnet($faker->dateTimeBetween('-2 year', 'now'))
                   ->setPhoto($photo);

            $manager->persist($carnet);

            // Ajoutez cette ligne pour créer une référence au carnet
            $this->addReference('carnet_' . $i, $carnet);
        }

        $manager->flush();
    }
}
