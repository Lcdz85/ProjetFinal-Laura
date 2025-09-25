<?php

namespace App\DataFixtures;

use App\Entity\Utilisateur;
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
        
        

        for ($i=1; $i<=60; $i++)
        {
            $comment = new Comment();
            $utilisateur = $this->getReference("user_". rand(1,5), Utilisateur::class);
            $post = $this->getReference("post_". rand(1,30), Post::class);

            $comment->setDateComment($faker->dateTimeBetween('-2 year', 'now'))
                 ->setTexte($faker->text(rand(30,150)))
                 ->setPost($post)
                 ->setUtilisateur($utilisateur);
            $post->addComment($comment);
            $utilisateur->addCommentCree($comment);
                 
            for ($j=1; $j<=rand(0,5); $j++) {
                $user = $this->getReference("user_". rand(1,5), Utilisateur::class);
                $comment->addUserLike($user);
                $user->addLikedComment($comment);
            }
            
            $this->addReference("comment_" . $i, $comment);

            $manager->persist($comment);

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
