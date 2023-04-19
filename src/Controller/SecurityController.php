<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{

    #[Route('/login', name: 'security.login' ,methods: ['GET','POST'])]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        return $this->render('pages/security/login.html.twig', [
            'last_name'=>$authenticationUtils->getLastUsername(),
            'error'=>$authenticationUtils->getLastAuthenticationError()
        ]);
    }
    #[Route('/logout', name: 'security.logout', methods: ['GET'])]
    public function logout()
    {
        // controller can be blank: it will never be called!
        throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }

    #[Route('/signup', name: 'security.signup', methods: ['GET','POST'])]
    public function regisration( \Symfony\Component\HttpFoundation\Request $request, EntityManagerInterface $manager) : Response
    {
        $user = new User() ;
        $form = $this->createForm(RegistrationType::class, $user);
        $user->setRoles(['ROLE_USER']);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $user =  $form->getData();
            $manager->persist($user);
            $manager->flush();
            $this->addFlash(
                'success',
                'Votre compte a été crée avec succes'
            );
            return $this->redirectToRoute('security.login');
        }

        return $this->render('pages/security/registration.html.twig',[
            'form' => $form->createView()
        ]);
    }

}
