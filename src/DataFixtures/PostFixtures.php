<?php

namespace App\DataFixtures;

use App\Entity\Post;
use App\Repository\PostRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PostFixtures extends Fixture
{


    public function __construct(
        private readonly PostRepository $postRepository,
    )
    {
    }

    public function load(ObjectManager $manager): void
    {

        for($i = 1; $i <= 150; $i++) {
            $post = new Post(
                "title-". $i,
                "content-". $i,
                $i < 100
            );
            $manager->persist($post);
        }


        $manager->flush();
    }
}
