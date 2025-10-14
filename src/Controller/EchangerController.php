<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Annonce;
use App\Entity\Echanger;
use App\Form\EchangerType;
use App\Repository\EchangerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/messages')]
class EchangerController extends AbstractController
{
    // Poue envoyer un message à partir d’une annonce
        #[Route('/envoyer/{id}', name: 'app_message_envoyer', methods: ['GET', 'POST'])]
        public function envoyer(
            Annonce $annonce,
            Request $request,
            EntityManagerInterface $entityManager): Response {
   
            $user = $this->getUser();
            if (!$user) {
                $this->addFlash('error', 'Vous devez être connecté pour envoyer un message.');
                return $this->redirectToRoute('app_login');
            }

            $message = new Echanger();
            $message->setAnnonce($annonce);
            $message->setExpediteur($user);
            $message->setDestinataire($annonce->getUser()); 
            $message->setDateEnvoi(new \DateTime());

       
            $form = $this->createForm(EchangerType::class, $message);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->persist($message);
                $entityManager->flush();

                $this->addFlash('success', 'Votre message a été envoyé au vendeur.');
                return $this->redirectToRoute('annonce_show', ['id' => $annonce->getId()]);
            }

       
            return $this->render('echanger/envoyer.html.twig', [
                'annonce' => $annonce,
                'form' => $form->createView(),
            ]);
        }

    //  Pour Voir tous les messages reçus --> Avoir une boite de reception
        #[Route('/', name: 'app_message_reception', methods: ['GET'])]
        public function boiteReception(EchangerRepository $echangerRepository): Response
        {
            $user = $this->getUser();
            if (!$user) {
                $this->addFlash('error', 'Vous devez être connecté pour accéder à votre messagerie.');
                return $this->redirectToRoute('app_login');
            }

            $conversations = $echangerRepository->findConversationsByUser($user);

            return $this->render('echanger/reception.html.twig', [
                'conversations' => $conversations,
            ]);
        }

    //  Pour voir une conversation complète par annonce + expéditeur et avoir un fil 
        #[Route('/conversation/{annonceId}/{expediteurId}', name: 'app_message_conversation', methods: ['GET', 'POST'])]
        public function voirConversation(
            int $annonceId, int $expediteurId,
            EchangerRepository $echangerRepository, EntityManagerInterface $entityManager,
            Request $request ): Response 
        {
            $user = $this->getUser();
            if (!$user) {
                $this->addFlash('error', 'Vous devez être connecté pour accéder à cette conversation.');
                return $this->redirectToRoute('app_login');
            }


            $messages = $echangerRepository->findConversationMessages($annonceId, $user->getId(), $expediteurId);

            $annonce = $entityManager->getRepository(Annonce::class)->find($annonceId);
            $autreUtilisateur = $entityManager->getRepository(User::class)->find($expediteurId);


            $nouveauMessage = new Echanger();
            $nouveauMessage->setAnnonce($annonce);
            $nouveauMessage->setExpediteur($user);
            $nouveauMessage->setDestinataire($autreUtilisateur);
            $nouveauMessage->setDateEnvoi(new \DateTime());


            $form = $this->createForm(EchangerType::class, $nouveauMessage);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->persist($nouveauMessage);
                $entityManager->flush();

                return $this->redirectToRoute('app_message_conversation', [
                    'annonceId' => $annonceId,
                    'expediteurId' => $expediteurId,
                ]);
            }

            return $this->render('echanger/conversation.html.twig', [
                'messages' => $messages,
                'annonce' => $annonce,
                'autreUtilisateur' => $autreUtilisateur,
                'form' => $form->createView(),
            ]);
        }
        
}