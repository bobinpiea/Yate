<?php

namespace App\Controller;

use App\Entity\Sujet;
use App\Form\SujetType;
use App\Entity\Categorie;
use App\Repository\SujetRepository;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class ForumController extends AbstractController
{
    /*
    #[Route('/forum', name: 'app_forum')]
    public function index(): Response
    {
        return $this->render('forum/index.html.twig', [
            'controller_name' => 'ForumController',
        ]);
    }*/

    /* CATEGORIES */

        /* Liste des catégories */
            #[Route('/forum/categories', name: 'app_forum_categories')]
                public function categories(CategorieRepository $categorieRepository): Response
                {
                    $categories = $categorieRepository->findAll();

                    return $this->render('forum/categories.html.twig', [
                        'categories' => $categories,
                    ]);
                }

    /* SUJETS - CRUD */ 


    // Liste des sujets - Dans une catégorie
        #[Route('/forum/categories/{id}/sujets', name: 'app_forum_sujets')]
        public function sujets( Categorie $categorie, SujetRepository $sujetRepository): Response {

            $sujets = $sujetRepository->findBy(['categorie' => $categorie]);

            return $this->render('forum/sujets.html.twig', [
                'categorie' => $categorie,
                'sujets' => $sujets,
            ]);
        }

    // Création des sujets - Dans une Catégorie 

        #[Route('/forum/categories/{id}/sujets/new', name: 'app_forum_sujet_new')]
        public function newSujet( Categorie $categorie, Request $request, EntityManagerInterface $entityManager ): Response {

            $sujet = new Sujet();

            $form = $this->createForm(SujetType::class, $sujet);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $sujet->setCategorie($categorie);
                $sujet->setAuteur($this->getUser());
                $sujet->setDateCreation(new \DateTime());
                $sujet->setBloque(false);

                $entityManager->persist($sujet);
                $entityManager->flush();


                return $this->redirectToRoute('forum_sujets', [
                    'id' => $categorie->getId(),
                ]);
            }

            return $this->render('forum/sujet_new.html.twig', [
                'form' => $form->createView(),
                'categorie' => $categorie,
            ]);
        }


    /* MESSAGES */




}
