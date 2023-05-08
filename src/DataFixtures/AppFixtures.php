<?php

namespace App\DataFixtures;

use App\Entity\Mark;
use App\Entity\Recipe;
use App\Entity\User;
use Faker\Factory;
use Faker\Generator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Ingredient;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private Generator $faker;


    public function __construct(){
        $this->faker = Factory::create('fr_FR');
    }
    public function load(ObjectManager $manager): void
    {

        $users = [];
        // Users

        for ($k = 0; $k < 10 ; $k++){
            $user = new User();


            $user->setFullName($this->faker->name())
                ->setPseudo(mt_rand(0,1) === 1 ? $this->faker->firstName() : null )
                ->setEmail($this->faker->email())
                ->setRoles(['ROLE_USER'])
                ->setPlainPassword("password");
            $users[] =$user;
            $manager->persist($user);
        }


        $ingredients = [];
        for($i = 0; $i < 50; $i++){
            $ingredient = new Ingredient();
            $ingredient->setName($this->faker->word());
            $ingredient->setPrice(mt_rand(1, 100));
            $ingredient->setUser($users[mt_rand(0, count($users) - 1 )]);


            $ingredients[] = $ingredient;
            $manager->persist($ingredient);
        }

        // $product = new Product();
        // recipe
        $recipes = [];
        for($i = 0; $i < 25; $i++){
            $recipe = new Recipe();
            $recipe->setName($this->faker->words(2,true));
            $recipe->setDescription($this->faker->text(150));
            $recipe->setDifficulty(mt_rand(1, 6));


            $recipe->setPrice(mt_rand(1, 100))
                   ->setTime(mt_rand(0,1) == 1 ? mt_rand(1,1441) : null)
                   ->setNbPeople(mt_rand(0,1) == 1 ? mt_rand(1,49) : null)
                   ->setIsFavourite(mt_rand(0,1) == 1)
                   ->setIsPublic(mt_rand(0,1) == 1);

            for ($k = 0; $k < mt_rand(5, 15); $k++){
                $recipe->addIngredient($ingredients[mt_rand(0,count($ingredients) - 1)]);
            }
            $recipe->setUser($users[mt_rand(0, count($users) - 1 )]);
            $recipes[] = $recipe;
            $manager->persist($recipe);
        }

        // Mark

        foreach ($recipes as $recipe ){
            $mark = new Mark();
            $mark->setRecipe($recipe);
            $mark->setUser($users[mt_rand(0, count($users) - 1 )]);
            $mark->setMark(mt_rand(0,5));
            $manager->persist($mark);
        }


        $manager->flush();
    }
}
