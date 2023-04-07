<?php

namespace App\DataFixtures;

use Faker\Factory;
use Faker\Generator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Ingredient;


class AppFixtures extends Fixture
{
    private Generator $faker;

    public function __construct(){
        $this->faker = Factory::create('fr_FR');
    }
    public function load(ObjectManager $manager): void
    {
        for($i = 0; $i < 50; $i++){
            $ingredient = new Ingredient();
            $ingredient->setName($this->faker->words(3,true));
            $ingredient->setPrice(mt_rand(1, 100));
            $manager->persist($ingredient);
        }

        // $product = new Product();


        $manager->flush();
    }
}
