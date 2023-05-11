<?php

namespace App\Tests\Unit;

use App\Entity\Mark;
use App\Entity\Recipe;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class RecipeTest extends KernelTestCase
{

    public function  getEntity():Recipe{
        return (new Recipe())
            ->setName('Titre de la recette')
            ->setDescription("Description de la recette")
            ->setIsFavourite(true)
            ->setCreatedAt(new \DateTimeImmutable())
            ->setUpdatedAt(new \DateTimeImmutable());
    }
    public function testEntityIsValid(): void
    {
        $kernel = self::bootKernel();
        $container = static ::getContainer();
        $recipe = $this->getEntity();
        $errors = $container->get('validator')->validate($recipe);

        $this->assertCount(1, $errors);

    }
    public function testInvalidName(): void
    {
        $kernel = self::bootKernel();
        $container = static ::getContainer();
        $recipe = $this->getEntity()
                ->setName('');
        $errors = $container->get('validator')->validate($recipe);

        $this->assertCount(3, $errors);

    }

    public function testGetAverage(): void
    {
        $kernel = self::bootKernel();
        $user = static ::getContainer()->get("doctrine.orm.entity_manager")->find(User::class,1);
        $recipe = $this->getEntity();

        for ($i = 0; $i < 5; $i++){
            $mark = new Mark();
            $mark->setMark($i)
                    ->setUser($user)
                    ->setRecipe($recipe);
            $recipe->addMark($mark);
        }


        $this->assertTrue(2.0 === $recipe->getAverageMark() );

    }
}
