<?php

namespace App\DataFixtures;

use App\Entity\Post;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PostFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $post = new Post();
        $post->setTitle('Eiffel Tower from Trocadero');
        $post->setDescription('Spectacular view at sunset time of the beautiful Eiffel Tower in Paris.');
        $post->setLatitude('48.862336');
        $post->setLongitude('2.288084');
        $post->setPhotoFilename('IMG-6312-5fdbcd3583103.jpg');

        $manager->persist($post);

        $manager->flush();
    }
}
