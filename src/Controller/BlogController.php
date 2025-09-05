<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Form\BlogType;
use App\Repository\BlogRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response; // Répresente la reponse de HTTP enovoyée au navigateur
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
        #[Route('/conseil_expert', name: 'conseil_expert')]
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
    #[Route('/conseil_deco', name: 'conseil_deco')]
    public function showDecoArticles(BlogRepository $blogRepository): Response
    {
        // On récupère les articles dont la catégorie est "déco"
        $articles = $blogRepository->findBy(['categorie' => 'deco']);

         // On lui passe les articles récupérés
        return $this->render('blog/conseil_deco.html.twig', [
            'blogs' => $articles,
        ]);
    }

        #[Route('/blog/new', name: 'blog_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        //1. Création d'un objet blog, car on part d'un article vide
        $blog = new Blog();

        // 2. On lit le formulaire à notre objet $blog
        $form = $this->createForm(BlogType::class, $blog);

        // 3. on remplit avec les données du formulaire (Hydratation) 
        $form->handleRequest($request);

        // 4. Vérification si formulaire soumis et valide (qu'il respecte les contraintes)
        if ($form->isSubmitted() && $form->isValid()) {
            // 5. L'auteur connecté
            $blog->setAuteur($this->getUser());

            // 6. Enregistrer ds la bdd
            $entityManager->persist($blog);
            $entityManager->flush();

            // 7. Redirection
            // selon la catégorie choisie
                if ($blog->getCategorie() === 'expertise') {
                    return $this->redirectToRoute('conseil_expert');
                } else {
                    return $this->redirectToRoute('conseil_deco');
                }
            }

        // 8. Sinon, on affiche le formulaire à nouveau
        return $this->render('blog/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}
