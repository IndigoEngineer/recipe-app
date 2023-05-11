<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ContactTest extends WebTestCase
{
    public function contactTest(): void
    {

        $client = static::createClient();
        $crawler = $client->request('POST', '/contact');

        $this->assertResponseIsSuccessful();

        //Recupérer le formulaire
        $button = $crawler->selectButton("Submit");
        $form = $button->form();

        //Remplir le formulaire
        $form["contact[fullName]"] =  "Jean Dupons";
        $form["contact[email]"]    =  "johnatan@hebreuX.com";
        $form["contact[subject]"]  =  "Fuck my decisions";
        $form["contact[message]"]  =  "Bla bla car bla bla car";
        //Envoyer
//        $client->submit($form);
        // Voir l status code
//        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);

        // Verifier l'envoie de l'email

//        $this->assertEmailCount(1);

//        $client->followRedirect();
//        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);

        //voir le message

//        $this->assertSelectorTextContains(
//            'div.alert.alert-dismissible.alert-success.mt-4',
//            'Message envoyé'
//        );
    }
}
