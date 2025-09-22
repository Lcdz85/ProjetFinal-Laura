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
        for ($i = 1; $i <= 5; $i++) {
            $token = $faker->regexify('[A-Za-z0-9]{50}');

            $invitation = new Invitation();
            $invitation->setEmail($faker->email())
                ->setToken($token)
                ->setDateInvite($faker->dateTimeBetween('-2 year', 'now'))
                ->setCarnet($this->getReference("carnet_" . rand(1, count($manager->getRepository(Carnet::class)->findAll())), Carnet::class));

            $this->addReference('invite_' . $i, $invitation);

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
