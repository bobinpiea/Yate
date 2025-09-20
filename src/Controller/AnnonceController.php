<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Form\AnnonceAjaxType;
use App\Repository\AnnonceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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

                //  On génère une référence unique de type RFXXXXXX (ex: RF5F3D9A7)
                $reference = $this->generateUniqueReference($entityManager);
                $annonce->setReference($reference);

                $annonce->setTransaction($form->get('transaction')->getData());

                $annonce->setNatureBien($form->get('nature')->getData());

                $annonce->setUser($this->getUser());

                $entityManager->persist($annonce);
     
                $entityManager->flush();

                return $this->redirectToRoute('app_home');
            }

            return $this->render('annonce/new.html.twig', [
                'form' => $form->createView(),
            ]);
        }

        #[Route('/annonce/new/preview', name: 'annonce_preview', methods: ['POST'])]
        public function previewAnnonce(Request $request, EntityManagerInterface $entityManager): JsonResponse
        {
            $form = $this->createForm(AnnonceAjaxType::class, null, [
                'method' => 'POST',
                'entityManager' => $entityManager,
            ]);

            // Laisse Symfony binder correctement FormData (inclut CSRF + fichiers si besoin)
            $form->handleRequest($request);

            // Optionnel: si besoin de forcer la (re)construction même sans submit
            // if (!$form->isSubmitted()) { $form->submit($request->request->get($form->getName(), [])); }

            if (!$form->isSubmitted()) {
    $form->submit($request->request->all($form->getName()));
}

            $partial = $form->get('details');

            return new JsonResponse([
                'form' => $this->renderView('annonce/_details_fields.html.twig', [
                    'form' => $partial->createView(),
                ]),
            ]);
        }
 
    
    /*
     *  Fonction privée qui génère une référence aléatoire UNIQUE
     * Exemple de format : RF5F3D9A7
     */
    private function generateUniqueReference(EntityManagerInterface $entityManager): string
    {
        do {
            
            $random = strtoupper(bin2hex(random_bytes(4))); 
            $reference = 'RF' . $random;

            
            $existingAnnonce = $entityManager->getRepository(Annonce::class)
                ->findOneBy(['reference' => $reference]);
        } while ($existingAnnonce !== null); 

        return $reference;
    }




    /* LISTE DES BIENS PAR TYPES DE TRANSACTION */

    
        // Affiche toutes les annonces de type Achat
            #[Route('/annonces/achat', name: 'app_annonce_liste_achat')]
            public function listeAchat(AnnonceRepository $annonceRepository): Response
            {
        
                $annonces = $annonceRepository->findByTransactionNom('Vente');

                return $this->render('annonce/liste_achat.html.twig', [
                    'annonces' => $annonces,
                ]);
            }

        
        // Affiche toutes les annonces de type Location
            #[Route('/annonces/location', name: 'app_annonce_liste_location')]
            public function listeLocation(AnnonceRepository $annonceRepository): Response
            {
                $annonces = $annonceRepository->findByTransactionNom('Location');

                return $this->render('annonce/liste_location.html.twig', [
                    'annonces' => $annonces,
                ]);
            }


        // Affiche toutes les annonces de type Colocation
            #[Route('/annonces/colocation', name: 'app_annonce_liste_colocation')]
            public function listeColocation(AnnonceRepository $annonceRepository): Response
            {
                $annonces = $annonceRepository->findByTransactionNom('Colocation');

                return $this->render('annonce/liste_colocation.html.twig', [
                    'annonces' => $annonces,
                ]);
            }


    
}