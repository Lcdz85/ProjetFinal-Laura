<?php

namespace App\DataFixtures;

use App\Entity\Utilisateur;
use App\Entity\Carnet;
use App\Entity\Post;
use App\Entity\Comment;
use App\Entity\Invitation;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;


class UtilisateurFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_BE');

        for ($i=0; $i<10; $i++)
        {
            $utilisateur = new Utilisateur();
            $utilisateur->setUsername($faker->unique()->userName)
                        ->setEmail($faker->unique()->freeEmail)
                        ->setPhoto("https://i.pravatar.cc/300?u=".$i)
                        ->setPassword($faker->password);
        }     

        $manager->flush();
    }
}
