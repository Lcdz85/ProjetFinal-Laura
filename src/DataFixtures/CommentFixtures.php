<?php

namespace App\DataFixtures;

use App\Entity\Carnet;
use App\Entity\Post;
use App\Entity\Comment;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;


class CommentFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_BE');
        
        $carnets = $manager->getRepository(Carnet::class)->findAll();

        for ($i=0; $i<25; $i++)
        {
            $comment = new Comment();

            $comment->setDateComment($faker->dateTimeBetween('-2 year', 'now'))
                 ->setTexte($faker->text(rand(50,200)))
                 ->setPost($this->getReference("post_". $i, Post::class));
            
            $manager->persist($comment);

    
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
