<?php

namespace App\Controller;

use App\Entity\NatureBien;
use App\Form\NatureBienType;
use App\Repository\NatureBienRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/nature/bien')]
final class NatureBienController extends AbstractController
{
    #[Route(name: 'app_nature_bien_index', methods: ['GET'])]
    public function index(NatureBienRepository $natureBienRepository): Response
    {
        return $this->render('nature_bien/index.html.twig', [
            'nature_biens' => $natureBienRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_nature_bien_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $natureBien = new NatureBien();
        $form = $this->createForm(NatureBienType::class, $natureBien);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($natureBien);
            $entityManager->flush();

            return $this->redirectToRoute('app_nature_bien_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('nature_bien/new.html.twig', [
            'nature_bien' => $natureBien,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_nature_bien_show', methods: ['GET'])]
    public function show(NatureBien $natureBien): Response
    {
        return $this->render('nature_bien/show.html.twig', [
            'nature_bien' => $natureBien,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_nature_bien_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, NatureBien $natureBien, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(NatureBienType::class, $natureBien);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_nature_bien_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('nature_bien/edit.html.twig', [
            'nature_bien' => $natureBien,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_nature_bien_delete', methods: ['POST'])]
    public function delete(Request $request, NatureBien $natureBien, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$natureBien->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($natureBien);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_nature_bien_index', [], Response::HTTP_SEE_OTHER);
    }
}
