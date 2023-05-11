<?php

namespace App\Tests\Functional\Admin;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ContactTest extends WebTestCase
{
    public function testIfCrudIsHere(): void
    {

        $client = static::createClient();
        /** @var UrlGeneratorInterface $urlGenerator */
        $urlGenerator = $client->getContainer()->get('router');

        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
        $user = $entityManager->find(User::class,1);
        $client->loginUser($user);

        $crawler = $client->request('GET', '/admin');

        $this->assertResponseIsSuccessful();

        $crawler = $client->clickLink("Messages");
        $this->assertResponseIsSuccessful();

        $crawler = $client->click($crawler->filter(".action-new")->link());
        $this->assertResponseIsSuccessful();


        $crawler = $client->request('GET', '/admin');
        $crawler = $client->clickLink("Messages");
        $crawler = $client->click($crawler->filter(".action-edit")->link());
        $this->assertResponseIsSuccessful();
        //Recupérer le formulaire

    }
}
