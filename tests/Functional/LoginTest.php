<?php

namespace App\Tests\Functional;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class LoginTest extends WebTestCase
{
    public function testIfLoginIsValid(): void
    {
        $client = static::createClient();
        //get route by Url generator
        /** @var UrlGeneratorInterface  $urlGenerator */
        $urlGenerator = $client->getContainer()->get('router');

        $crawler = $client->request('GET',$urlGenerator->generate('security.login'));

        $form = $crawler->filter("form[name=login]")->form([
            "_username" => "admin@symrecipe.com",
            "_password" => "password"
        ]);

        //form
        $client->submit($form);
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);

        //redirect
        $client->followRedirect();

        $this->assertRouteSame('home.index');
    }

    public function testIfLoginPasswordIsWrong(): void
    {
        $client = static::createClient();
        //get route by Url generator
        /** @var UrlGeneratorInterface  $urlGenerator */
        $urlGenerator = $client->getContainer()->get('router');

        $crawler = $client->request('GET',$urlGenerator->generate('security.login'));

        $form = $crawler->filter("form[name=login]")->form([
            "_username" => "admin@symrecipe.com",
            "_password" => "password_"
        ]);

        //form
        $client->submit($form);
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);

        //redirect
        $client->followRedirect();

        $this->assertRouteSame('security.login');

        $this->assertSelectorTextContains(
            'div.alert-danger',
            'Invalid credentials.'
        );
    }
}
