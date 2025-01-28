<?php

namespace App\Controller;

use App\Entity\TeteDeLit;
use App\Form\TeteDeLitType;
use App\Repository\TeteDeLitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/tete_de_lit')]
final class TeteDeLitController extends AbstractController
{
    #[Route(name: 'app_tete_de_lit_index', methods: ['GET'])]
    public function index(TeteDeLitRepository $teteDeLitRepository): Response
    {
        return $this->render('tete_de_lit/index.html.twig', [
            'tete_de_lits' => $teteDeLitRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_tete_de_lit_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $teteDeLit = new TeteDeLit(); // Le tableau $pictures est initialisé dans l'entité
    
        $form = $this->createForm(TeteDeLitType::class, $teteDeLit);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Gestion des fichiers uploadés
            $pictureFiles = $form->get('pictures')->getData();
    
            if ($pictureFiles) {
                foreach ($pictureFiles as $pictureFile) {
                    // Générer un nom unique pour chaque fichier
                    $originalFilename = pathinfo($pictureFile->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $safeFilename . '-' . uniqid() . '.' . $pictureFile->guessExtension();
    
                    try {
                        // Déplacer chaque fichier vers le répertoire configuré
                        $pictureFile->move(
                            $this->getParameter('pictures_directory'),
                            $newFilename
                        );
    
                        // Ajouter le nom du fichier au tableau `$pictures` dans l'entité
                        $teteDeLit->addPicture($newFilename);
                    } catch (FileException $e) {
                        // Gestion des erreurs si nécessaire
                        throw new \Exception('Erreur lors du téléchargement d\'un fichier.');
                    }
                }
            }
    
            // Persister et sauvegarder l'entité
            $entityManager->persist($teteDeLit);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_tete_de_lit_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->render('tete_de_lit/new.html.twig', [
            'tete_de_lit' => $teteDeLit,
            'form' => $form,
        ]);
    }
    
    

    #[Route('/{id}', name: 'app_tete_de_lit_show', methods: ['GET'])]
    public function show(TeteDeLit $teteDeLit): Response
    {
        return $this->render('tete_de_lit/show.html.twig', [
            'tete_de_lit' => $teteDeLit,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_tete_de_lit_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, TeteDeLit $teteDeLit, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TeteDeLitType::class, $teteDeLit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_tete_de_lit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('tete_de_lit/edit.html.twig', [
            'tete_de_lit' => $teteDeLit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_tete_de_lit_delete', methods: ['POST'])]
    public function delete(Request $request, TeteDeLit $teteDeLit, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$teteDeLit->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($teteDeLit);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_tete_de_lit_index', [], Response::HTTP_SEE_OTHER);
    }
}
