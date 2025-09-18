<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Form\AnnonceAjaxType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class AnnonceController extends AbstractController
{

    /*
     * Méthode qui affiche et gère le formulaire de création d'une annonce nouvelle
     */
        #[Route('/annonce/new', name: 'app_annonce_new', methods: ['GET', 'POST'])]
        public function newAnnonce(Request $request, EntityManagerInterface $entityManager): Response
        {
            if (!$this->getUser()) {
                $this->addFlash('warning', 'Vous devez être connecté pour pouvoir publier une annonce.');
                return $this->redirectToRoute('app_login');
            }

            // On stocke le formulaire dans la $ form
            $form = $this->createForm(AnnonceAjaxType::class, null, [
                'entityManager' => $entityManager,
            ]);

            //  connecte les données de la requête HTTP avec mon objet $form.
            $form->handleRequest($request); 

            // Si soumis et valide, on peut enregistrer
            if ($form->isSubmitted() && $form->isValid()) {
            
                $donneesAnnonce = $form->get('details')->getData();
        
                $annonce = new Annonce();
    
                // Parcourt chaque champ du sous-formulaire 
                foreach ($donneesAnnonce as $champ => $valeur) { 
                   // Construit  le nom du setter
                    $setter = 'set' . ucfirst($champ);
                // Vérifie que la méthode existe bien dans l'entité Annonce
                    if (method_exists($annonce, $setter)) {
                // Si oui, on appelle automatiquement le setter avec sa valeur
                // ex : $annonce->setSuperficie(120);
                        $annonce->$setter($valeur);
                    }
                }

                // On definit la date de publication à mtn 
                $annonce->setDatePublication(new \DateTime());

                $entityManager->persist($annonce);
                $entityManager->flush();

                return $this->redirectToRoute('accueil');
            }

            return $this->render('annonce/new.html.twig', [
                'form' => $form->createView(),
            ]);
        }

        #[Route('/annonce/new/preview', name: 'annonce_preview', methods: ['POST'])]
        public function previewAnnonce(Request $request, EntityManagerInterface $entityManager): Response
        {
           
            $form = $this->createForm(AnnonceAjaxType::class, null, [
                'method' => 'POST',
                'entityManager' => $entityManager,
            ]);

            // On soumet les données reçues dans la requête pour déclencher les événements PRE_SUBMIT
            $form->submit($request->request->all());

            // Extraction du bloc "details" mis à jour  selon les choix de l'utilisateur
            $formulairePartiel = $form->get('details');

            // Rendu partiel du sous-formulaire (nécessite un fichier Twig _details_fields.html.twig)
            return new JsonResponse([
                'form' => $this->renderView('annonce/_details_fields.html.twig', [
                    'form' => $formulairePartiel->createView(),
                ]),
            ]);
        }
}