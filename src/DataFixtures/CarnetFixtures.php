<?php

namespace App\DataFixtures;

use App\Entity\Carnet;
use App\Entity\Post;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class CarnetFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {   
        $faker = Factory::create('fr_BE');
        $modeleTitres = ["Sur la route de ",
                        "Souvenirs de ",
                        "Escapade en ",
                        "Chroniques de ",
                        "À la découverte de ",];

        $allPostsRefs = range(1, 30);
        shuffle($allPostsRefs);
        
        for ($i=1; $i<=12; $i++)
        {
            $lieu = $faker->boolean() ? $faker->unique()->city() : $faker->unique()->country();
            $photo = "https://picsum.photos/800/600?random=" . rand(1, 200);

            $carnet = new Carnet();
            $carnet->setTitre($modeleTitres[rand(0,count($modeleTitres)-1)] . $lieu)
                   ->setDateCarnet($faker->dateTimeBetween('-2 year', 'now'))
                   ->setPhoto($photo);
               
            $postRefs = array_splice($allPostsRefs, 0, rand(2,3));
            foreach ($postRefs as $ref) {
                $post = $this->getReference('post_' . $ref, Post::class);
                $carnet->addPost($post);
                $post->setCarnet($carnet);
            }

            $manager->persist($carnet);

            // Ajoutez cette ligne pour créer une référence au carnet
            $this->addReference('carnet_' . $i, $carnet);
        }

        $manager->flush();
    }

    public function getDependencies():array
    {
        return [
            PostFixtures::class,
        ];
    }
}
