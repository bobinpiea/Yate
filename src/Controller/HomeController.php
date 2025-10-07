<?php

namespace App\Controller;


// OK 

use App\Entity\Annonce;
use App\Repository\BlogRepository;
use App\Repository\UserRepository;
use App\Repository\PhotoRepository;
use App\Repository\AnnonceRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class HomeController extends AbstractController
{
    /* Page D'accueil */
        #[Route('/home', name: 'app_home')]
        public function index(
        AnnonceRepository $annonceRepository, 
        BlogRepository $blogRepository,
        ): Response
        {
            $villes = $annonceRepository->findHuitDernieresVilles();

            $annoncesVente = $annonceRepository->findByTransactionNom('Vente');
            $annoncesLocation = $annonceRepository->findByTransactionNom('Location');
            $annoncesColocation = $annonceRepository->findByTransactionNom('Colocation');

            $articlesJuridique = $blogRepository->findBy(['categorie' => 'expertise']);
            $articlesDeco = $blogRepository->findBy(['categorie' => 'deco']);

            return $this->render('home/index.html.twig', [

                'annoncesVente' => $annoncesVente,
                'annoncesLocation' => $annoncesLocation,        
                'annoncesColocation' => $annoncesColocation,

                'articlesJuridique' => $articlesJuridique,
                'articlesDeco' => $articlesDeco,

                'villes' => $villes,
 
            ]);
        }
  
    /* ROUTE FOOTER */

        #[Route('/about', name: 'app_about')]
        public function about(): Response 
        {
            return $this->render('home/about.html.twig');
        }

        #[Route('/nous-rejoindre', name: 'app_nous_rejoindre')]
        public function nousRejoindre(): Response
        {
            return $this->render('home/nous_rejoindre.html.twig');
        }

        #[Route('/nos-engagements', name: 'app_nos_engagements')]
        public function nosEngagements(): Response
        {
            return $this->render('home/nos_engagements.html.twig');
        }

        #[Route('/boite-idees', name: 'app_boite_idees')]
        public function boiteIdees(): Response
        {
            return $this->render('home/boite_idees.html.twig');
        }

        #[Route('/mentions-legales', name: 'app_legal')]
        public function legal(): Response
        {
            return $this->render('home/legal.html.twig');
        }

        #[Route('/politique-de-confidentialite', name: 'app_privacy')]
        public function privacy(): Response
        {
            return $this->render('home/privacy.html.twig');
        }

}
