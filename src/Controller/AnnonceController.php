<?php

namespace App\Controller;

use App\Entity\Photo;
use App\Entity\Annonce;
use App\Form\AnnonceAjaxType;
use App\Service\PictureService;
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
        public function newAnnonce(Request $request, EntityManagerInterface $entityManager, PictureService $pictureService): Response
        {
            if (!$this->getUser()) {
                $this->addFlash('warning', 'Veuillez vous connecter afin de pouvoir publier une annonce.');
                return $this->redirectToRoute('app_login');
            }

            $form = $this->createForm(AnnonceAjaxType::class, null, [
                'entityManager' => $entityManager,
            ]);

            $form->handleRequest($request); 

            if ($form->isSubmitted() && $form->isValid()) {
                $donneesAnnonce = $form->get('details')->getData();
                $annonce = new Annonce();

                foreach ($donneesAnnonce as $champ => $valeur) { 
                    $setter = 'set' . ucfirst($champ);
                    if (method_exists($annonce, $setter)) {
                        $annonce->$setter($valeur);
                    }
                }

                $annonce->setDatePublication(new \DateTime());
                $reference = $this->generateUniqueReference($entityManager);
                $annonce->setReference($reference);
                $annonce->setTransaction($form->get('transaction')->getData());
                $annonce->setNatureBien($form->get('nature')->getData());
                $annonce->setUser($this->getUser());

                /* Gestion des photos */
                $photos = $form->get('photosUpload')->getData();

                if (!$photos || count($photos) < 1) {
                    $this->addFlash('danger', 'Vous devez ajouter au moins une photo.');
                    return $this->render('annonce/new.html.twig', [
                        'form' => $form->createView(),
                    ]);
                }

                if (count($photos) > 10) {
                    $this->addFlash('danger', 'Vous ne pouvez pas ajouter plus de 10 photos.');
                    return $this->render('annonce/new.html.twig', [
                        'form' => $form->createView(),
                    ]);
                }

                foreach ($photos as $photo) {
                    if ($photo && $photo->isValid()) {
                        $nomFichier = $pictureService->add($photo, 'annonces');

                        $image = new Photo();
                        $image->setUrl($nomFichier);
                        $image->setAnnonce($annonce);

                        $entityManager->persist($image);
                    }
                }

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

        $form->handleRequest($request);

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

    #[Route('/annonces/achat', name: 'app_annonce_liste_achat')]
    public function listeAchat(AnnonceRepository $annonceRepository): Response
    {
        $annonces = $annonceRepository->findByTransactionNom('Vente');

        return $this->render('annonce/liste_achat.html.twig', [
            'annonces' => $annonces,
        ]);
    }

    #[Route('/annonces/location', name: 'app_annonce_liste_location')]
    public function listeLocation(AnnonceRepository $annonceRepository): Response
    {
        $annonces = $annonceRepository->findByTransactionNom('Location');

        return $this->render('annonce/liste_location.html.twig', [
            'annonces' => $annonces,
        ]);
    }

    #[Route('/annonces/colocation', name: 'app_annonce_liste_colocation')]
    public function listeColocation(AnnonceRepository $annonceRepository): Response
    {
        $annonces = $annonceRepository->findByTransactionNom('Colocation');

        return $this->render('annonce/liste_colocation.html.twig', [
            'annonces' => $annonces,
        ]);
    }

    /* DETAILS D'UN BIEN : PAGE D'AFFICHAGE D'UNE ANNONCE  */

    #[Route('/annonce/{id}', name: 'annonce_show')]
    public function show(Annonce $annonce): Response
    {
        return $this->render('annonce/show.html.twig', [
            'annonce' => $annonce,
        ]);
    }
}