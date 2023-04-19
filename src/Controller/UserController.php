<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserPasswordType;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{
    #[Route('/user/edit/{id}', name: 'user.edit' , methods: ['GET','POST'])]
    public function index(
        User $user,
        Request $request,
        EntityManagerInterface $manager,
        UserPasswordHasherInterface $passwordHasher): Response
    {
        if (!$this->getUser()){
            return  $this->redirectToRoute('security.login');
        }

        if ($this->getUser() !== $user){
            return  $this->redirectToRoute('recipe.index');
        }


        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        $plain = $form->get('plainPassword')->getData();


        if ($form->isSubmitted() && $form->isValid()){
            $plaintextPassword = $form->get('plainPassword')->getData();
            if (!$passwordHasher->isPasswordValid($user, $plaintextPassword)) {
                $this->addFlash(
                    'warning',
                    'Mot de passe incorrect'
                );
                return $this->redirectToRoute('user.edit',array('id' => $user->getId()));
            }
            else{
                $user = $form->getData();
                $manager->persist($user);
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

    #[Route('/user/edit-password/{id}', name: 'user.edit.password' , methods: ['GET','POST'])]
    public  function editPassword(User $user, Request $request, EntityManagerInterface $manager,UserPasswordHasherInterface $passwordHasher):Response
    {
        if (!$this->getUser()){
            return  $this->redirectToRoute('security.login');
        }

        if ($this->getUser() !== $user){
            return  $this->redirectToRoute('recipe.index');
        }

        $form = $this->createForm(UserPasswordType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $plaintextPassword = $form->get('plainPassword')->getData();

            if (!$passwordHasher->isPasswordValid($user, $plaintextPassword)) {
                $this->addFlash(
                    'warning',
                    'Mot de passe incorrect'
                );
                return $this->redirectToRoute('user.edit.password',array('id' => $user->getId()));
            }
            else{
                $new_password = $form->getData()['newPassword'];
                $newHashedPassword = $passwordHasher->hashPassword(
                    $user,
                    $new_password
                );
                $user->setPassword($newHashedPassword);
                $manager->persist($user);
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
