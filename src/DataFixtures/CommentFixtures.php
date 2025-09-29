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
        
        

        // une partie des comments va sur les posts
        for ($i=1; $i<=45; $i++)
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
                 
            //ajouter de 0 à 5 like au comment
            for ($j=1; $j<=rand(0,5); $j++) {
                $user = $this->getReference("user_". rand(1,5), Utilisateur::class);
                $comment->addUserLike($user);
                $user->addLikedComment($comment);
            }

            $manager->persist($comment);
        }

        $manager->flush();

        // les 15 comments restants seront sur les 45 premiers comments
        for ($i=1; $i<=15; $i++)
        {
            $comment = new Comment();

            $post = $this->getReference("post_". rand(1,30), Post::class);
            $postComments = $post->getComments();
            if ($postComments->count() > 0) {
                $parent = $postComments->get(rand(0, $postComments->count() - 1));
                $utilisateur = $this->getReference("user_". rand(1,5), Utilisateur::class);

                $comment->setDateComment($faker->dateTimeBetween('-2 year', 'now'))
                     ->setTexte($faker->text(rand(30,150)))
                     ->setUtilisateur($utilisateur)
                     ->setPost($parent->getPost())
                     ->setParent($parent);
    
                $utilisateur->addCommentCree($comment);
                $parent->addComment($comment);

                for ($j=1; $j<=rand(0,5); $j++) {
                    $user = $this->getReference("user_". rand(1,5), Utilisateur::class);
                    $comment->addUserLike($user);
                    $user->addLikedComment($comment);
                }
    
                $manager->persist($comment);
            }
        }

        $manager->flush(); 

    }
    public function getDependencies(): array
    {
        return [
            UtilisateurFixtures::class,
            PostFixtures::class,
            CarnetFixtures::class,
        ];
    }
}
