<?php

namespace App\Controller;

use App\Repository\BlogRepository;
use Symfony\Component\HttpFoundation\Response; // Répresente la reponse de HTTP enovoyée au navigateur
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController; // Hérite de cette méthode pour avoir droit à des méthodes (ex: Render)

final class BlogController extends AbstractController // Final car on ne pourra pas hériter de cette classe plus tard - Extends car on hérite de Abstract
{
    /*
    #[Route('/blog', name: 'app_blog')]
    public function index(): Response
    {
        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
        ]);
    }
    */

    /* PAGE : CONSEIL D'EXPERT - ( Elle sera publique ) */
        
   //  Cette méthode affichera tous les articles de la catégorie expertise
        #[Route('/conseil_expert', name: 'blog_conseil_expert')]
    public function showExpertArticles(BlogRepository $blogRepository): Response
    {
        // On récupère les articles dont la catégorie est "expertise"
        $articles = $blogRepository->findBy(['categorie' => 'expertise']);

          // On lui passe les articles récupérés
        return $this->render('blog/conseil_expert.html.twig', [
            'blogs' => $articles,
        ]);
    }

    /* PAGE : CONSEIL DECO -  (Elle sera publique également) */

    //  Cette méthode affichera tous les articles de la catégorie déco
    #[Route('/conseil_deco', name: 'blog_conseil_deco')]
    public function showDecoArticles(BlogRepository $blogRepository): Response
    {
        // On récupère les articles dont la catégorie est "déco"
        $articles = $blogRepository->findBy(['categorie' => 'deco']);

         // On lui passe les articles récupérés
        return $this->render('blog/conseil_deco.html.twig', [
            'blogs' => $articles,
        ]);
    }

}
