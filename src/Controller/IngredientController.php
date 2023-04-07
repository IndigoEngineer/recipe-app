<?php

namespace App\Controller;

use App\Form\IngredientType;
use App\Repository\IngredientRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Ingredient;
use Knp\Component\Pager\PaginatorInterface;
class IngredientController extends AbstractController
{
    #[Route('/ingredient', name: 'ingredient.index', methods: ['GET'])]
    public function index(IngredientRepository $repository,PaginatorInterface $paginator, Request $request): Response
    {
//
        $ingredients = $paginator->paginate(
            $repository->findAll(), /* query NOT result */
            $request->query->getInt('page', 1),/*page number*/
            10 /*limit per page*/
        );
        return $this->render('pages/ingredient/index.html.twig', [
            'controller_name' => 'IngredientController',
            'ingredients' => $ingredients,
        ]);
    }

    #[Route('/ingredient/nouveau', 'ingredient.new' , methods: ['GET','POST'])]
    public function new(Request $request, EntityManagerInterface $manager): Response{
        $ingredient = new  Ingredient();
        $form = $this->createForm(IngredientType::class, $ingredient);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
//            dd($form->getData());
            $ingredient = $form->getData();
            $manager->persist($ingredient);
            $manager->flush();
            $this->addFlash(
                'success',
                'Votre ingrédient a été créer avec success'
            );
            return $this->redirectToRoute('ingredient.index');
        }

        return $this->render('pages/ingredient/new.html.twig',[
            'form'=>$form->createView()
        ]);
    }

    #[Route('/ingredient/edit/{id}', 'ingredient.edit' , methods: ['GET','POST'])]
    public function edit(Ingredient $ingredient,
                         Request $request,
                         EntityManagerInterface $manager
    ): Response {

        $form = $this->createForm(IngredientType::class, $ingredient);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
//            dd($form->getData());
            $ingredient = $form->getData();
            $manager->persist($ingredient);
            $manager->flush();
            $this->addFlash(
                'success',
                'Votre ingrédient a été modifié avec success'
            );
            return $this->redirectToRoute('ingredient.index');
        }

        return  $this->render('pages/ingredient/edit.html.twig',[
                'form' => $form->createView()
        ]);
    }

    #[Route('/ingredient/delete/{id}', 'ingredient.delete' , methods: ['GET'])]
    public function delete(Ingredient $ingredient,
                           EntityManagerInterface $manager
    ):response{
        $manager->remove($ingredient);
        $manager->flush();
        $this->addFlash(
            'success',
            'Votre ingrédient a été supprimé avec success'
        );
        return $this->redirectToRoute('ingredient.index');
    }
}
