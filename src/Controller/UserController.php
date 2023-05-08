<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserPasswordType;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{
    #[Security("is_granted('ROLE_USER') and user === originalUser ")]
    #[Route('/user/edit/{id}', name: 'user.edit' , methods: ['GET','POST'])]
    public function index(
        User $originalUser,
        Request $request,
        EntityManagerInterface $manager,
        UserPasswordHasherInterface $passwordHasher): Response
    {
        $form = $this->createForm(UserType::class, $originalUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $plaintextPassword = $form->get('plainPassword')->getData();
            if (!$passwordHasher->isPasswordValid($originalUser, $plaintextPassword)) {
                $this->addFlash(
                    'warning',
                    'Mot de passe incorrect'
                );
                return $this->redirectToRoute('user.edit',array('id' => $originalUser->getId()));
            }
            else{
                $originalUser = $form->getData();
                $manager->persist($originalUser);
                $manager->flush();
                $this->addFlash(
                    'success',
                    'Vos informations ont été modifiés avec success'
                );
                return $this->redirectToRoute('ingredient.index');
            }


        }
        return $this->render('/pages/user/edit.html.twig', [
            'form'=> $form
        ]);

    }

    #[Security("is_granted('ROLE_USER') and user === originalUser ")]
    #[Route('/user/edit-password/{id}', name: 'user.edit.password' , methods: ['GET','POST'])]
    public  function editPassword(User $originalUser, Request $request, EntityManagerInterface $manager,UserPasswordHasherInterface $passwordHasher):Response
    {

        $form = $this->createForm(UserPasswordType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $plaintextPassword = $form->get('plainPassword')->getData();

            if (!$passwordHasher->isPasswordValid($originalUser, $plaintextPassword)) {
                $this->addFlash(
                    'warning',
                    'Mot de passe incorrect'
                );
                return $this->redirectToRoute('user.edit.password',array('id' => $originalUser->getId()));
            }
            else{
                $newPassword = $form->getData()['newPassword'];
                $originalUser->setUpdatedAt(new \DateTimeImmutable);
                $originalUser->setPlainPassword($newPassword);

                $manager->persist($originalUser);
                $manager->flush();

                $this->addFlash(
                    'success',
                    'Votre mot de passe a été modifié avec success'
                );
                return $this->redirectToRoute('ingredient.index');
            }

        }
        return $this->render('pages/user/edit_password.html.twig',[
            'form'=>$form->createView()
        ]);
    }
}
