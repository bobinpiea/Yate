<?php

namespace App\Controller;

use App\Form\ProfileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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

}