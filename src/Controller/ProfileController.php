<?php

namespace App\Controller;

use App\Form\ProfileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

final class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(): Response
    {
        return $this->render('profile/index.html.twig', [
            'controller_name' => 'ProfileController',
        ]);
    }

    /* Pour afficher le profil */
            #[Route('/profile', name: 'app_profile')]
        public function show(): Response
        {
            $user = $this->getUser();

            if (!$user) {
                return $this->redirectToRoute('app_login');
            }

            return $this->render('profile/show.html.twig', [
                'user' => $user,
                'annonces' => $user->getAnnonces(), // A VERIFIER
            ]);
        }

    /* Pour editer le profil */
        #[Route('/profile/edit', name: 'app_profile_edit')]
        public function edit(Request $request, EntityManagerInterface $entityManager): Response
        {
            $user = $this->getUser();

            if (!$user) {
                return $this->redirectToRoute('app_login');
            }

            $form = $this->createForm(ProfileType::class, $user);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->flush();
                return $this->redirectToRoute('app_profile');
            }

            return $this->render('profile/edit.html.twig', [
                'form' => $form->createView(),
            ]);           
        
        }

    /* Pour supprimer le profil */
    #[Route('/profil/supprimer', name: 'app_user_delete', methods: ['POST'])]
    public function supprimerCompte(
        Request $request,
        EntityManagerInterface $entityManager,
        TokenStorageInterface $tokenStorage,
        RequestStack $requestStack
    ): Response {
        $user = $this->getUser();

        if (!$user) {
            $this->addFlash('error', 'Vous devez être connecté pour supprimer votre compte.');
            return $this->redirectToRoute('app_login');
        }

        if ($this->isCsrfTokenValid('delete_account', $request->request->get('_token'))) {

            //  Déconnexion propre avant suppression
            $tokenStorage->setToken(null); //  supprime l’utilisateur 
            $requestStack->getSession()->invalidate(); //détruit la session (cookies inclus).

            // Suppression de l’utilisateur
            $entityManager->remove($user);
            $entityManager->flush();

            $this->addFlash('success', 'Votre compte a bien été supprimé conformément au RGPD.');
            return $this->redirectToRoute('app_home');
        }

        $this->addFlash('error', 'Échec de la suppression de votre compte.');
        return $this->redirectToRoute('app_profile');
    }

}