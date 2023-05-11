<?php

namespace App\Controller;

use App\Entity\Contact;

use App\Form\ContactType;
use App\Service\MailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact', methods: ['GET','POST'])]
    public function index(Request $request,
                          EntityManagerInterface $manager,
                          MailService $mailService): Response
    {
        $contact = new Contact();
        if ($this->getUser()){
            $user = $this->getUser();
            $contact->setFullName(fullName: $user->getFullName())
                     ->setEmail(email: $user->getEmail());
        }
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);
//        dd($contact);

        if ($form->isSubmitted() && $form->isValid()){
            $manager->persist($contact);
            $manager->flush();

            // Send email
            $mailService->sendMail(
                $contact->getEmail(),
                'admin@symrecipe.com',
                $contact->getSubject(),
                'emails/contact.html.twig',
                [
                    'expiration_date' => new \DateTime('+7 days'),
                    'contact' => $contact,
                ]);


            $this->addFlash(
                'success',
                'Message envoyé'
            );
            return $this->redirectToRoute("app_contact");
        }
        return $this->render('pages/contact/index.html.twig', [
            'contactForm'=> $form->createView()
        ]);
    }
}
