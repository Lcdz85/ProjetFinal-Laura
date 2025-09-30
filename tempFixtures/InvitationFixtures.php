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

        for ($i = 1; $i <= 5; $i++) {

            $token = $faker->regexify('[A-Za-z0-9]{50}');

            $user = $this->getReference("user_" . rand(1, count($manager->getRepository(Utilisateur::class)->findAll())), Utilisateur::class);

            $invitation = new Invitation();
            $invitation->setEmail($faker->email())
                        ->setToken($token)
                        ->setDateInvite($faker->dateTimeBetween('-2 year', 'now'))
                        ->setUtilisateur($user);
            $user->addInvitation($invitation);
            
            $usersCarnets = $user->getCarnetsCrees()->toArray();
            if (count($usersCarnets) > 0) {
                $carnet = $usersCarnets[rand(0,count($usersCarnets)-1)];
                $invitation->setCarnet($carnet);
                $carnet->addInvitation($invitation);
                
                $manager->persist($carnet);
            }

            $this->addReference('invite_' . $i, $invitation);

            $manager->persist($invitation);
            
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
