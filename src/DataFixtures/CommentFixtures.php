<?php

namespace App\DataFixtures;

use App\Entity\Carnet;
use App\Entity\Post;
use App\Entity\Comment;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CommentFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_BE');
        $photo = "https://picsum.photos/800/600?random=" . rand(200, 500);
        // Récupérer tous les carnets
        $carnets = $manager->getRepository(Carnet::class)->findAll();

        for ($i=0; $i<25; $i++)
        {
            $comment = new Comment();

            $comment->setDateComment($faker->dateTimeBetween('-2 year', 'now'))
                 ->setTexte($faker->text(rand(50,200)))
                 ->setPost($this->getReference($post));
            
            $manager->persist($comment);

    
        }

        $manager->flush();
    }
}
