<?php

namespace App\Controller;

use App\Entity\Mark;
use App\Form\MarkType;
use App\Form\RecipeType;
use App\Repository\MarkRepository;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use App\Entity\Recipe;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class RecipeController extends AbstractController
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher){
        $this->passwordHasher = $passwordHasher;
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/recipe', name: 'recipe.index',methods: ['GET'])]
    public function index(RecipeRepository $repository,PaginatorInterface $paginator, Request $request): Response
    {
        $recipes = $paginator->paginate(
            $repository->findBy(["user"=>$this->getUser()]), /* query NOT result */
            $request->query->getInt('page', 1),/*page number*/
            10 /*limit per page*/
        );
        return $this->render('pages/recipe/index.html.twig', [
            'controller_name' => 'RecipeController',
            'recipes'=> $recipes
        ]);
    }

    #[Route('/recipe/public', name: 'recipe.public',methods: ['GET'])]
    public  function indexPublic (RecipeRepository $repository,PaginatorInterface $paginator, Request $request):Response {

        $recipes = $paginator->paginate(
            $repository->findPublicRecipe(null),
            $request->query->getInt('page', 1),/*page number*/
            10 /*limit per page*/
        );

        return $this->render("pages/recipe/index_public.html.twig",[
            'recipes'=>$recipes
        ]);
    }
    #[Security("is_granted('ROLE_USER') and  recipe.getIsPublic() === true")]
    #[Route('/recipe/show/{id}', name: 'recipe.show',methods: ['GET','POST'])]
    public function show(Mark $mark, Request $request,
                         Recipe $recipe,EntityManagerInterface $manager,
                         MarkRepository $markRepository):Response
    {
        $mark = new Mark();
        $form = $this->createForm(MarkType::class, $mark);
        $form->handleRequest( $request) ;
        if ($form->isSubmitted() && $form->isValid() ){
            $mark->setUser($this->getUser())
                  ->setRecipe($recipe);

            $existingMark = $markRepository->findOneBy([
                'user'=>$this->getUser(),
                'recipe'=>$recipe
            ]);

            if(!$existingMark){
                $manager->persist($mark);
            }else{
                $existingMark->setMark($mark->getMark());
            }
            $manager->flush();
            $this->addFlash(
                'success',
                'Votre note a été prise en compte'
            );

        }
            return $this->render("pages/recipe/show.html.twig",[
                "recipe" => $recipe,
                "notationForm" =>$form
            ]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/recipe/new', name: 'recipe.new',methods: ['GET','POST'])]
    public function new(Request $request, EntityManagerInterface $manager) : Response
    {
        $recipe = new Recipe();
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest( $request) ;

        if ($form->isSubmitted() && $form->isValid()){
            $recipe = $form->getData();
            $recipe->setUser($this->getUser());
            $manager->persist($recipe);
            $manager->flush();
            $this->addFlash(
                'success',
                'Votre recette have been created with success'
            );
            return $this->redirectToRoute('recipe.index',[], 303);
        }

        return $this->render("pages/recipe/new.html.twig",[
            'form'=>$form
        ]);
    }

    #[Security("is_granted('ROLE_USER') and user === recipe.getUser() ")]
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
            return $this->redirectToRoute('recipe.index',[], 303);
        }

        if ($form->isSubmitted() && !$form->isValid()){
            $response = new Response(null,422);

            return $this->render("pages/recipe/edit.html.twig",[
                'form' => $form
            ], $response);
        }

        return  $this->render('pages/recipe/edit.html.twig',[
            'form' => $form
        ]);
    }

    #[Security("is_granted('ROLE_USER') and user === recipe.getUser() ")]
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

