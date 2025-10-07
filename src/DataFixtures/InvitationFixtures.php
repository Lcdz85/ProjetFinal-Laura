<?php

namespace App\DataFixtures;

use App\Entity\Invitation;
use App\Entity\Utilisateur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Factory;

class InvitationFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_BE');

        $count = 0;
        while ($count < 5) {

            $token = $faker->regexify('[A-Za-z0-9]{50}');
            $user = $this->getReference("user_" . rand(1, count($manager->getRepository(Utilisateur::class)->findAll())), Utilisateur::class);
            $usersCarnets = $user->getCarnetsCrees()->toArray();
            
            if (count($usersCarnets) === 0) {continue;}
            
            $carnet = $usersCarnets[rand(0,count($usersCarnets)-1)];

            $invitation = new Invitation();
            $invitation->setEmail($faker->email())
                        ->setToken($token)
                        ->setDateInvite($faker->dateTimeBetween('-2 year', 'now'))
                        ->setUtilisateur($user);
            $user->addInvitation($invitation);
            $carnet->addInvitation($invitation);

            $manager->persist($invitation);
            
            $count++;
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UtilisateurFixtures::class
        ];
    }
}
