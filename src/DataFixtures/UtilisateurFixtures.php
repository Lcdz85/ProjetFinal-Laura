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
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class UtilisateurFixtures extends Fixture implements DependentFixtureInterface
{
    private $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_BE');
        
        $allCarnets = range(1, 12);
        shuffle($allCarnets);

        for ($i = 1; $i <= 5; $i++) 
        {
            $utilisateur = new Utilisateur();
            $utilisateur->setUsername($faker->unique()->userName)
                        ->setEmail($faker->unique()->freeEmail)
                        ->setPhoto("https://i.pravatar.cc/300?u=".$i)
                        ->setPassword($this->hasher->hashPassword($utilisateur, 'Password'.$i));
            
            // Carnets créés par l'utilisateur
            $creesRefs = array_splice($allCarnets, 0, rand(0,5));
            foreach ($creesRefs as $ref) {
                $carnet = $this->getReference('carnet_' . $ref, Carnet::class);
                $utilisateur->addCarnetCree($carnet); 
                $carnet->setUtilisateur($utilisateur);
            }
            // 2) Carnets accessibles (≠ carnets créés)
            $carnetsRestants = array_diff($allCarnets, $creesRefs);
            shuffle($carnetsRestants);
            $NonCreesRefs = array_slice($carnetsRestants, 0, rand(0,3));
            foreach ($NonCreesRefs as $ref) {
                $carnet = $this->getReference('carnet_' . $ref, Carnet::class);
                $utilisateur->addCarnetAcces($carnet);
                $carnet->addUserAcces($utilisateur);
            }

            $this->addReference('user_' . $i, $utilisateur);
            
            $manager->persist($utilisateur);
            $manager->persist($carnet);
        }

        $manager->flush();


    }

    public function getDependencies(): array
    {
        return [
            CarnetFixtures::class,
            PostFixtures::class,
            CommentFixtures::class,
            InvitationFixtures::class,
        ];
    }
}
