<?php

namespace App\Tests\Functional;

use App\Entity\Ingredient;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class IngredientTest extends WebTestCase
{
    public function testIfCreateIngredientIsSuccessful(): void
    {
        $client = static::createClient();

        //Recp url genrator
        /** @var UrlGeneratorInterface $urlGenerator */
        $urlGenerator = $client->getContainer()->get('router');

        // REcup entity manager
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
        $user = $entityManager->find(User::class,1);

        $client->loginUser($user);
        // se rendre sur la page de création d'un ingredient
        $crawler = $client->request(Request::METHOD_GET,$urlGenerator->generate('ingredient.new'));

        // Gérer le formulaire
        $form = $crawler->filter('form[name=ingredient]')
                        ->form([
                            "ingredient[name]" => "molou",
                            "ingredient[price]" => 8
                        ]);
        $client->submit($form);

        // gérer la redirection
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        // Gérer l'alerte et la route
        $client->followRedirect();

        $this->assertRouteSame("ingredient.index");

        $this->assertSelectorTextContains(
           'div.alert-dismissible',
           'Votre ingrédient a été créer avec success'
        );
    }
    public function testIfIngredientUpdateIsSuccessful(): void {
        $client = static::createClient();

        //Recp url genrator
        /** @var UrlGeneratorInterface $urlGenerator */
        $urlGenerator = $client->getContainer()->get('router');

        // REcup entity manager
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
        $user = $entityManager->find(User::class,1);

        $client->loginUser($user);

        $recipe =  $entityManager->getRepository(Ingredient::class)->findOneBy([
                "user" => $user
            ]
        );


        $crawler = $client->request(Request::METHOD_POST,$urlGenerator->generate('ingredient.edit',["id" => $recipe->getId() ]));

        $form = $crawler->filter("form[name=ingredient]")->form([
            "ingredient[name]" => "Un ingredient 2",
            "ingredient[price]" => floatval(5)
        ]);


        $client->submit($form);


        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);

        $client->followRedirect();
        $this->assertRouteSame("ingredient.index");

        $this->assertResponseIsSuccessful();

        $this->assertSelectorExists('.table-dark');
//        $this->assertCount(10,$ingredients);

    }
    public function testIfIngredientDeleteIsSuccessful(): void {
        $client = static::createClient();

        //Recp url genrator
        /** @var UrlGeneratorInterface $urlGenerator */
        $urlGenerator = $client->getContainer()->get('router');

        // REcup entity manager
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
        $user = $entityManager->find(User::class,1);

        $client->loginUser($user);

        $recipe =  $entityManager->getRepository(Ingredient::class)->findOneBy([
                "user" => $user,
                "name" => "Un ingredient 2",
            ]
        );

        $crawler = $client->request(
            Request::METHOD_GET,
            $urlGenerator->generate('ingredient.delete',["id" => $recipe->getId()])
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);

        $client->followRedirect();

        $this->assertRouteSame("ingredient.index");

        $this->assertResponseIsSuccessful();


    }
}
