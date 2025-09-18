<?php

namespace App\DataFixtures;

use App\Entity\Invitation;
use App\Entity\Carnet;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Factory;

class InvitationFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_BE');
        for ($i = 1; $i <= 50; $i++) {
            $token = $faker->regexify('[A-Za-z0-9]{50}');

            $invitation = new Invitation();
            $invitation->setEmail($faker->email())
                ->setToken($token)
                ->setDateInvite($faker->dateTimeBetween('-2 year', 'now'))
                ->setActif(true)
                ->setCarnet($this->getReference("carnet_" . rand(0, 9), Carnet::class));

            $manager->persist($invitation);
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
