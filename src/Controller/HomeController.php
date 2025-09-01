<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig');
    }

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
}
