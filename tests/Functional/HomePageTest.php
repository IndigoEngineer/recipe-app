<?php

namespace App\Tests\Functional;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomePageTest extends WebTestCase
{
    public function testOnPageElmnt(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneBy([
            'email' => 'mmasson@leroux.fr'
        ]);

        $testAdmin = $userRepository->findOneBy([
            "email"=>"admin@symrecipe.com"
        ]);

        //Connection
        $client->loginUser($testAdmin);

        $crawler = $client->request('GET', '/');
        $button = $crawler->filter('.btn');
        $recipes = $crawler->filter('.card');
        $adminButton  = $crawler->filter('a[href^="/admin"]');

        $this->assertCount(1,$button);
        $this->assertCount(3,$recipes);
        $this->assertCount(1,$adminButton);
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Bienvenue sur SymRecipe!');
    }
}
