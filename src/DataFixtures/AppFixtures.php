<?php

namespace App\DataFixtures;


use App\Entity\Comment;
use App\Entity\Nem;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $faker = \Faker\Factory::create();

        for ($i=0; $i<8; $i++){
            $nem = new Nem();
            $nem->setName($faker->name());
            $nem->setPrice($faker->randomDigit());

            $manager->persist($nem);

            for ($j=0; $j<8; $j++){
                $comment = new Comment();
                $comment->setContent($faker->sentence);
                $comment->setNem($nem);
                $manager->persist($comment);
            }
        }

        //$manager->flush();
    }
}
