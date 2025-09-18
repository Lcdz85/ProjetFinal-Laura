<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Utilisateur;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Faker\Factory;
class UtilisateurFixtures extends Fixture
{
    
    private $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_BE');
        
        for ($i=1; $i<=5; $i++) {
            $utilisateur = new Utilisateur();
            $utilisateur->setEmail('user' . $i . '@gmail.com');
            $utilisateur->setNom('nom'.$i);
            $utilisateur->setDateNaissance($faker->dateTimeBetween('-80 year', '-18 year'));
            // $utilisateur->setRoles();
            $utilisateur->setPassword($this->hasher->hashPassword($utilisateur,'lePassword'.$i));
            $this->addReference('user' . $i, $utilisateur);

             $manager->persist($utilisateur);
        }

        $manager->flush();
    }
}

