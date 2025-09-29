<?php

namespace App\DataFixtures;

use App\Entity\Post;
use App\Entity\Carnet;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class PostFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_BE');
        $photo = "https://picsum.photos/800/600?random=" . rand(200, 500);
        

        for ($i=1; $i<=30; $i++)
        {
            $post = new Post();

            $post->setTitre($faker->sentence(rand(3, 6)))
                 ->setDatePost($faker->dateTimeBetween('-2 year', 'now'))
                 ->setTexte($faker->text(rand(50,200)))
                 ->setPhoto($photo)
                 ->setLatitude($faker->latitude())
                 ->setLongitude($faker->longitude());

            // Associer un Carnet existant (créé par CarnetFixtures)
            $carnets = $manager->getRepository(Carnet::class)->findAll();
            $carnet = $carnets[array_rand($carnets)];
            $post->setCarnet($carnet);

            
            $manager->persist($post);

            // Ajoutez cette ligne pour créer une référence au post
            $this->addReference('post_' . $i, $post);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CarnetFixtures::class,
        ];
    }
}
