<?php

namespace App\Controller; // Ce fichier appartient à l'espace controller de mon Application

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils; // Récupérer les erreurs de connexion + les identifiants saisis

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one = Permet d'afficher un message si la tentative de connexion a échoué
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user = pre-rempli le champ email si la personne s'est trompé sur le mdp
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
