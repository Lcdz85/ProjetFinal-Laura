<?php

namespace App\DataFixtures;

use App\Entity\Photo;
use App\Entity\Post;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class PhotoFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // We created 30 posts in PostFixtures with references post_1..post_30
        $postCount = 30;

        for ($i = 1; $i <= 300; $i++) {
            $photo = new Photo();

            //$url = 'https://picsum.photos/800/600?random=' . (200 + $i);
            $url = 'imageFile' . $i;
            $photo->setImageFile($url);

            // Link to a random existing Post by reference
            
            $randomPostIndex = random_int(1, $postCount);
            $postTemp = $this->getReference('post_' . $randomPostIndex, Post::class);
            // dump(count($postTemp->getPhotos()));
            while (count($postTemp->getPhotos()) >= 3){
                // dd();
                // dump (" trop de photos------------------------------------------------");
                $randomPostIndex = random_int(1, $postCount);
                $postTemp = $this->getReference('post_' . $randomPostIndex, Post::class);
            }
            
            $photo->setPost($postTemp);

            $manager->persist($photo);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            PostFixtures::class,
        ];
    }
}
