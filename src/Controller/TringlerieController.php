<?php

namespace App\Controller;

use App\Entity\Tringlerie;
use App\Form\TringlerieType;
use App\Repository\TringlerieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/tringlerie')]
final class TringlerieController extends AbstractController
{
    #[Route(name: 'app_tringlerie_index', methods: ['GET'])]
    public function index(TringlerieRepository $tringlerieRepository): Response
    {
        return $this->render('tringlerie/index.html.twig', [
            'tringleries' => $tringlerieRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_tringlerie_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $tringlerie = new Tringlerie();
        $form = $this->createForm(TringlerieType::class, $tringlerie);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer le fichier d'image depuis le formulaire (champ "picture")
            $pictureFile = $form->get('picture')->getData();
    
            if ($pictureFile) {
                // Générer un nom de fichier unique et sûr
                $originalFilename = pathinfo($pictureFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $pictureFile->guessExtension();
    
                try {
                    // Déplacer le fichier dans le répertoire configuré
                    $pictureFile->move(
                        $this->getParameter('images_directory'), // Paramètre défini dans config/services.yaml
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Gestion des erreurs si le fichier ne peut pas être déplacé
                    throw new \Exception('Erreur lors du téléchargement du fichier.');
                }
    
                // Enregistrer le nom du fichier dans l'entité
                $tringlerie->setPicture($newFilename); // Assure-toi que l'entité a une méthode setPicture()
            }
    
            // Persister et sauvegarder l'entité
            $entityManager->persist($tringlerie);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_tringlerie_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->render('tringlerie/new.html.twig', [
            'tringlerie' => $tringlerie,
            'form' => $form,
        ]);
    }
    

    #[Route('/{id}', name: 'app_tringlerie_show', methods: ['GET'])]
    public function show(Tringlerie $tringlerie): Response
    {
        return $this->render('tringlerie/show.html.twig', [
            'tringlerie' => $tringlerie,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_tringlerie_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Tringlerie $tringlerie, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TringlerieType::class, $tringlerie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_tringlerie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('tringlerie/edit.html.twig', [
            'tringlerie' => $tringlerie,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_tringlerie_delete', methods: ['POST'])]
    public function delete(Request $request, Tringlerie $tringlerie, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tringlerie->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($tringlerie);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_tringlerie_index', [], Response::HTTP_SEE_OTHER);
    }
}
