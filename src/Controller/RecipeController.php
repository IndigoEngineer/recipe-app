<?php

namespace App\Controller;

use App\Form\RecipeType;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use App\Entity\Recipe;
class RecipeController extends AbstractController
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher){
        $this->passwordHasher = $passwordHasher;
    }

    #[Route('/recipe', name: 'recipe.index',methods: ['GET'])]
    public function index(RecipeRepository $repository,PaginatorInterface $paginator, Request $request): Response
    {
        $recipes = $paginator->paginate(
            $repository->findAll(), /* query NOT result */
            $request->query->getInt('page', 1),/*page number*/
            10 /*limit per page*/
        );
        return $this->render('pages/recipe/index.html.twig', [
            'controller_name' => 'RecipeController',
            'recipes'=> $recipes
        ]);
    }

    #[Route('/recipe/new', name: 'recipe.new',methods: ['GET','POST'])]
    public function new(Request $request, EntityManagerInterface $manager) : Response
    {
        $recipe = new Recipe();
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest( $request) ;

        if ($form->isSubmitted() && $form->isValid()){
            $recipe = $form->getData();
            $manager->persist($recipe);
            $manager->flush();
            $this->addFlash(
                'success',
                'Votre recette have been created with success'
            );
            return $this->redirectToRoute('recipe.index');
        }

        return $this->render("pages/recipe/new.html.twig",[
            'form'=>$form->createView()
        ]);
    }

    #[Route('/recipe/edit/{id}', 'recipe.edit' , methods: ['GET','POST'])]
    public function edit(recipe $recipe,
                         Request $request,
                         EntityManagerInterface $manager
    ): Response {

        $form = $this->createForm(RecipeType::class, $recipe);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
//            dd($form->getData());
            $recipe = $form->getData();
            $manager->persist($recipe);
            $manager->flush();
            $this->addFlash(
                'success',
                'Votre recette a été modifié avec success'
            );
            return $this->redirectToRoute('recipe.index');
        }

        return  $this->render('pages/recipe/edit.html.twig',[
            'form' => $form->createView()
        ]);
    }

    #[Route('/recipe/delete/{id}', 'recipe.delete' , methods: ['GET'])]
    public function delete(recipe $recipe,
                           EntityManagerInterface $manager
    ):response{
        $manager->remove($recipe);
        $manager->flush();
        $this->addFlash(
            'success',
            'Votre recette a été supprimé avec success'
        );
        return $this->redirectToRoute('recipe.index');
    }

}

