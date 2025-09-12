<?php

namespace App\Controller;

use DateTime;
use App\Entity\Sujet;
use DateTimeImmutable;
use App\Entity\Message;
use App\Form\SujetType;
use App\Entity\Categorie;
use App\Form\MessageType;
use App\Repository\SujetRepository;
use App\Repository\MessageRepository;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class ForumController extends AbstractController
{
 
    // AFFICHAGE DES CATEGORIES ET DES DIFFERENTS SUJETS

        /* Objetif : Qd on arrive sur la page "Forum"
        La liste des catégories est à gauche et tous les sujets à droite (classé par dernière création) */

            #[Route('/forum', name: 'app_forum')]
            public function index(CategorieRepository $categorieRepository,SujetRepository $sujetRepository): Response {

                $categories = $categorieRepository->findAll();
                $sujets = $sujetRepository->findBy([], ['dateCreation' => 'DESC']);

                return $this->render('forum/index.html.twig', [
                    'categories' => $categories,
                    'sujets' => $sujets,
                ]);
            }

    // AFFICHE DES SUJETS D'UNE CATEGORIE 

            #[Route('/forum/categories/{id}/sujets', name: 'app_forum_sujets')]
            public function sujets( Categorie $categorie, SujetRepository $sujetRepository, CategorieRepository $categorieRepository ): Response {

                $categories = $categorieRepository->findAll();

                $sujets = $sujetRepository->findBy(
                    ['categorie' => $categorie],
                    ['dateCreation' => 'DESC']
                );

                return $this->render('forum/sujets.html.twig', [
                    'categorie' => $categorie,
                    'sujets' => $sujets,
                    'categories' => $categories,
                ]);
            }


    // Création d'un nouveau sujet

        #[Route('/forum/sujet/new', name: 'app_forum_sujet_new')]
        public function newSujet( Request $request, EntityManagerInterface $entityManager ): Response {

            // On vérifie tout d'abord qu'un utilisateur est connecté ou pas, Sinon redirection 
            if (!$this->getUser()) {
                $this->addFlash('error', 'Vous devez être connecté pour créer un sujet.');
                return $this->redirectToRoute('app_login');
            }

            // Création de l'entité et du formulaire 
            $sujet = new Sujet();
            $sujet->setDateCreation(new \DateTime());
            $sujet->setBloque(false);
            
            $form = $this->createForm(SujetType::class, $sujet);
            $form->handleRequest($request);

            // Test de soumission et de validation 
            if ($form->isSubmitted() && $form->isValid()) {
                
                $texteMessage = $form->get('premierMessage')->getData();

                $message = new Message();
                $message->setTexte($texteMessage);
                $message->setAuteur($this->getUser()); 
                $message->setDateMessage(new \DateTime());
                $message->setSujet($sujet);

                $sujet->setAuteur($this->getUser());

      
                $entityManager->persist($sujet);
                $entityManager->persist($message);
                $entityManager->flush();

                return $this->redirectToRoute('app_forum_sujets', [
                    'id' => $sujet->getCategorie()->getId(),
                ]);
            }

            return $this->render('forum/sujet_new.html.twig', [
                'form' => $form->createView(),
            ]);
        }

    // AFFICHER UN SUJET
        #[Route('/forum/sujet/{id}', name: 'app_forum_sujet_show')]
        public function showSujet(Sujet $sujet, MessageRepository $messageRepository, CategorieRepository $categorieRepository): Response
        {
            $messages = $messageRepository->findBy(
                ['sujet' => $sujet],
                ['dateMessage' => 'ASC']
            );

            $categories = $categorieRepository->findAll();

            return $this->render('forum/sujet_show.html.twig', [
                'sujet' => $sujet,
                'messages' => $messages,
                'categories' => $categories,
            ]);
        }

    // MODIFIER SUJET - + 1er Message en meme temps
        #[Route('/forum/sujet/{id}/edit', name: 'app_forum_sujet_edit')]
        public function editSujet( Sujet $sujet, Request $request, EntityManagerInterface $entityManager, CategorieRepository $categorieRepository): Response {

            // On vérifie si l'utilisateur est connecté
            if (!$this->getUser()) {
                $this->addFlash('error', 'Vous devez être connecté pour modifier un sujet.');
                return $this->redirectToRoute('app_login');
            }

            // On vérifie sur l'utilisateur est bien l'auteur du sujet 
            if ($sujet->getAuteur() !== $this->getUser()) {
                $this->addFlash('error', 'Vous ne pouvez modifier que vos propres sujets.');
                return $this->redirectToRoute('app_forum_sujet_show', [
                    'id' => $sujet->getId(),
                ]);
            }

            // On veut tout modifier : Le sujet masi aussi le premier message possiblement
            $premierMessage = $sujet->getMessages()->first();

            // On se doit de préparer tout le formulaire en entier
            $form = $this->createForm(SujetType::class, $sujet);
            if ($premierMessage) {
                $form->get('premierMessage')->setData($premierMessage->getTexte());
            }

            $categories = $categorieRepository->findAll();

            $form->handleRequest($request);

            // On souhaite s'assuruer que le formulaire soumis est bien valide 
            if ($form->isSubmitted() && $form->isValid()) {
         
                if ($premierMessage) {
                    $premierMessage->setTexte($form->get('premierMessage')->getData());
                }

                $entityManager->flush();

                $this->addFlash('success', 'Modification effectuée avec succès.');
                return $this->redirectToRoute('app_forum_sujet_show', [
                    'id' => $sujet->getId(),
                ]);
            }

            return $this->render('forum/sujet_edit.html.twig', [
                'form' => $form->createView(),
                'sujet' => $sujet,
                'categories' => $categories,
            ]);
        }

    // MODIFIER LE message

    #[Route('/forum/message/{id}/edit', name: 'app_forum_message_edit')]
    public function editMessage( Message $message, Request $request, EntityManagerInterface $entityManager, CategorieRepository $categorieRepository): Response {

        // Obligation de vérifiacaiton si la pers est connecté
        if (!$this->getUser()) {
            $this->addFlash('error', 'Vous devez être connecté pour modifier un message.');
            return $this->redirectToRoute('app_login');
        }

        // Vérifier si l'utilisateur est bien l'auteur du message
        if ($message->getAuteur() !== $this->getUser()) {
            $this->addFlash('error', 'Vous ne pouvez modifier que vos propres messages.');
            return $this->redirectToRoute('app_forum_sujet_show', [
                'id' => $message->getSujet()->getId(),
            ]);
        }

        // Si c'est le cas, présentation du formulaire
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        // Vérification : le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Message modifié avec succès.');
            return $this->redirectToRoute('app_forum_sujet_show', [
                'id' => $message->getSujet()->getId(),
            ]);
        }

            $categories = $categorieRepository->findAll();

        //
        return $this->render('forum/message_edit.html.twig', [
            'form' => $form->createView(),
            'message' => $message,
            'categories' => $categories,
            
        ]);
    }

    // DELETE MESSAGE
        #[Route('/forum/message/{id}/delete', name: 'app_forum_message_delete', methods: ['POST'])]
        public function deleteMessage(Request $request, Message $message, EntityManagerInterface $entityManager): Response {

            if (!$this->getUser()) {
                $this->addFlash('error', 'Vous devez être connecté pour supprimer un message.');
                return $this->redirectToRoute('app_login');
            }

            if ($message->getAuteur() !== $this->getUser()) {
                $this->addFlash('error', 'Vous ne pouvez supprimer que vos propres messages.');
                return $this->redirectToRoute('app_forum_sujet_show', [
                    'id' => $message->getSujet()->getId(),
                ]);
            }

            // Vérification du token CSRF
            if ($this->isCsrfTokenValid('delete' . $message->getId(), $request->request->get('_token'))) {
                
                $sujet = $message->getSujet(); 
                $entityManager->remove($message);
                $entityManager->flush();

                // Vérifier si le sujet contient encore des messages
                if ($sujet->getMessages()->isEmpty()) {
                    $entityManager->remove($sujet);
                    $entityManager->flush();

                    $this->addFlash('success', 'Le sujet a également été supprimé');
                    return $this->redirectToRoute('app_forum');
                }

                $this->addFlash('success', 'Message supprimé avec succès.');
            }

            return $this->redirectToRoute('app_forum_sujet_show', [
                'id' => $message->getSujet()->getId(),
            ]);
        }

}
