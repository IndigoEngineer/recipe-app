<?php

namespace App\DataFixtures;

use App\Entity\Recipe;
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
        $ingredients = [];
        for($i = 0; $i < 50; $i++){
            $ingredient = new Ingredient();
            $ingredient->setName($this->faker->word());
            $ingredient->setPrice(mt_rand(1, 100));
            $ingredients[] = $ingredient;
            $manager->persist($ingredient);
        }

        // $product = new Product();
        // recipe
        for($i = 0; $i < 25; $i++){
            $recipe = new Recipe();
            $recipe->setName($this->faker->words(2,true));
            $recipe->setDescription($this->faker->text(150));
            $recipe->setDifficulty(mt_rand(1, 6));


            $recipe->setPrice(mt_rand(1, 100))
                   ->setTime(mt_rand(0,1) == 1 ? mt_rand(1,1441) : null)
                   ->setNbPeople(mt_rand(0,1) == 1 ? mt_rand(1,49) : null)
                   ->setIsFavourite(mt_rand(0,1) == 1);

            for ($k = 0; $k < mt_rand(5, 15); $k++){
                $recipe->addIngredient($ingredients[mt_rand(0,count($ingredients) - 1)]);
            }
            $manager->persist($recipe);
        }

        $manager->flush();
    }
}
