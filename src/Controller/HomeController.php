<?php

namespace App\Controller;

use App\Repository\RecipeRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class  HomeController extends AbstractController{
   #[Route('/', name: 'home.index', methods: ['GET'])]
    public function index(RecipeRepository $repository,
                          Request $request,
                          PaginatorInterface $paginator) : Response
    {
        $recipes = $paginator->paginate(
            $repository->findPublicRecipe(3),
            $request->query->getInt('page', 1),/*page number*/
            10 /*limit per page*/
        );

        return $this->render('pages/home.html.twig',[
            'recipes'=>$recipes
        ]);
    }
}
