<?php

namespace App\Controller;

use App\Entity\Module;
use App\Form\ModuleType;
use App\Entity\Categorie;
use App\Entity\Programme;
use App\Form\CategorieType;
use App\Form\ProgrammeType;
use App\Repository\ModuleRepository;
use App\Repository\CategorieRepository;
use App\Repository\ProgrammeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/* MODULE  */

    final class ModuleController extends AbstractController
    {
        /* LISTES */

            /* Liste MODULE */
                #[Route('/module', name: 'app_module')]
                public function index(ModuleRepository $moduleRepository): Response
                {
                    $modules = $moduleRepository->findAll();
                    return $this->render('module/index.html.twig', [
                        'modules' => $modules,
                    ]);
                }

            /* Liste PROGRAMME */
               #[Route('/programme', name: 'app_programme')]
                public function listProgrammes(ProgrammeRepository $programmeRepository): Response
                {
                    $programmes = $programmeRepository->findAll();
                    return $this->render('programme/index.html.twig', [
                        'programmes' => $programmes,
                    ]);
                }

            /* Liste CATEGORIE */
               #[Route('/categorie', name: 'app_categorie')]
                public function listCategories(CategorieRepository $categorieRepository): Response
                {
                    $categories = $categorieRepository->findAll();
                    return $this->render('categorie/index.html.twig', [
                        'categories' => $categories,
                    ]);
                }
            

        /* FORMULAIRES */  
             
            /* Formulaire MODULE */

                // FORMULAIRE + ÉDITION
                #[Route('/module/new', name: 'new_module')]
                #[Route('/module/{id}/edit', name: 'edit_module')]
                public function new(Request $request, EntityManagerInterface $entityManager, Module $module = null): Response
                {
                    // Si aucun objet Module n’est fourni, on en crée un vide
                    if (!$module) {
                        $module = new Module(); // On crée un nouvel objet Module s'il n'existe pas
                    }

                    // On génère le formulaire à partir de la classe ModuleType
                    // et on le lie directement à l’objet $module (création ou modification)
                    $form = $this->createForm(ModuleType::class, $module); // Génère un formulaire basé sur une classe

                    // On relie le formulaire aux données envoyées dans la requête HTTP
                    $form->handleRequest($request); // Lie les données reçues à l’objet

                    // Vérification : si le formulaire a été soumis ET que tous les champs sont valides
                    if ($form->isSubmitted() && $form->isValid()) {

                        // On récupère les données du formulaire (remplissent automatiquement $module)
                        $module = $form->getData();

                        // On prépare l’objet pour l’insérer (ou mettre à jour) en base de données
                        $entityManager->persist($module); // Prepare PDO

                        // On exécute l’insertion ou mise à jour dans la base de données
                        $entityManager->flush(); // Execute PDO

                        // Une fois terminé, on redirige vers la liste des modules
                        return $this->redirectToRoute('app_module');
                    }

                    // Si le formulaire n’a pas été soumis ou est invalide, on réaffiche la page avec le formulaire
                    return $this->render('module/new.html.twig', [
                        'formAddModule' => $form,
                        'edit' => $module->getId() // A Ne pas/plus oublier
                    ]);
                }
                       
            /* Formulaire PROGRAMME */

                // FORMULAIRE + ÉDITION
                #[Route('/programme/new', name: 'new_programme')]
                #[Route('/programme/{id}/edit', name: 'edit_programme')]
                public function newprogramme (Request $request, EntityManagerInterface $entityManager, Programme $programme = null): Response
                {
                    // Si aucun objet Programme n’est fourni, on en crée un vide
                    if (!$programme) {
                        $programme = new Programme();
                    }

                    // On génère le formulaire à partir de ProgrammeType et on le lie à l’objet
                    $form = $this->createForm(ProgrammeType::class, $programme);

                    // On lie les données de la requête HTTP (formulaire soumis)
                    $form->handleRequest($request);

                    // Si le formulaire est soumis ET valide
                    if ($form->isSubmitted() && $form->isValid()) {

                        // On récupère les données du formulaire dans l’objet $programme
                        $programme = $form->getData();

                        // On prépare puis envoie l'objet en base
                        $entityManager->persist($programme);
                        $entityManager->flush();

                        // Redirection vers une page au choix (ex : liste des programmes)
                        return $this->redirectToRoute('app_programme');
                    }

                    // Affichage du formulaire (mode création ou édition)
                    return $this->render('programme/new.html.twig', [
                        'formAddProgramme' => $form,
                        'edit' => $programme->getId(),
                    ]);
                }
                 
 
            /* Formulaire CATEGORIE */
              // FORMULAIRE + ÉDITIONs
                #[Route('/categorie/new', name: 'new_categorie')]
                #[Route('/categorie/{id}/edit', name: 'edit_categorie')]
                public function newCategorie(Request $request, EntityManagerInterface $entityManager, Categorie $categorie = null): Response
                {
                    // Si aucun objet Categorie n’est fourni, on en crée un vide
                    if (!$categorie) {
                        $categorie = new Categorie();
                    }

                    // On génère le formulaire à partir de CategorieType et on le lie à l’objet
                    $form = $this->createForm(CategorieType::class, $categorie);

                    // On lie les données de la requête HTTP (formulaire soumis)
                    $form->handleRequest($request);

                    // Si le formulaire est soumis ET valide
                    if ($form->isSubmitted() && $form->isValid()) {

                        // On récupère les données du formulaire dans l’objet $categorie
                        $categorie = $form->getData();

                        // On prépare puis envoie l'objet en base
                        $entityManager->persist($categorie);
                        $entityManager->flush();

                        // Redirection vers une page au choix (ex : liste des catégories)
                        return $this->redirectToRoute('app_categorie');
                    }

                    // Affichage du formulaire (mode création ou édition)
                    return $this->render('categorie/new.html.twig', [
                        'formAddCategorie' => $form,
                        'edit' => $categorie->getId(),
                    ]);
                }

            
        /* DETAILS */    

            /* Détail MODULE */
                    #[Route('/module/{id}', name: 'show_module')]
                public function show(Module $module): Response
                {
                    return $this->render('module/show.html.twig', [
                    'module' => $module,
                    ]);
                }
                        
            /* Détail PROGRAMME  - mais aucun détail à afficher de plus ? */
                #[Route('/programme/{id}', name: 'show_programme')]
                public function showProgramme(Programme $programme): Response
                {
                    return $this->render('programme/show.html.twig', [
                        'programme' => $programme,
                    ]);
                }
            

            /* Détail CATEGORIE  - mais aucun détail à afficher de plus ? */
                #[Route('/categorie/{id}', name: 'show_categorie')]
                public function showCategorie(Categorie $categorie): Response
                {
                    return $this->render('categorie/show.html.twig', [
                        'categorie' => $categorie,
                    ]);
                }

        /* SUPPRESSION */

            // SUPPRESSION d’un module
                #[Route('/module/{id}/delete', name: 'delete_module')]
                public function deleteModule(Module $module, EntityManagerInterface $em): Response
                {
                    $em->remove($module);     
                    $em->flush();  

                    // Message flash 
                    $this->addFlash('success', 'Module supprimé avec succès.');

                    // Redirection vers la liste des modules
                    return $this->redirectToRoute('app_module');
                }


            // SUPPRESSION d’un programme
                #[Route('/programme/{id}/delete', name: 'delete_programme')]
                public function deleteProgramme(Programme $programme, EntityManagerInterface $em): Response
                {
                    $em->remove($programme);     
                    $em->flush();  

                    // Message flash 
                    $this->addFlash('success', 'Programme supprimé avec succès.');

                    //  Redirection vers la liste
                    return $this->redirectToRoute('app_programme');
                }


            // SUPPRESSION d’une catégorie
                #[Route('/categorie/{id}/delete', name: 'delete_categorie')]
                public function deleteCategorie(Categorie $categorie, EntityManagerInterface $em): Response
                {
                    $em->remove($categorie);     
                    $em->flush();  

                    // Message flash 
                    $this->addFlash('success', 'Catégorie supprimée avec succès.');

                    // Redirection vers la liste des catégories
                    return $this->redirectToRoute('app_categorie');
                }

    }
