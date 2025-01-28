<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Entity\Banquette;
use App\Form\BanquetteType;
use App\Repository\BanquetteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/banquette')]
final class BanquetteController extends AbstractController
{
    #[Route(name: 'app_banquette_index', methods: ['GET'])]
    public function index(BanquetteRepository $banquetteRepository): Response
    {
        return $this->render('banquette/index.html.twig', [
            'banquettes' => $banquetteRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_banquette_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $banquette = new Banquette(); // Le tableau $pictures est initialisé dans l'entité
    
        $form = $this->createForm(BanquetteType::class, $banquette);
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
                        $banquette->addPicture($newFilename);
                    } catch (FileException $e) {
                        // Gestion des erreurs si nécessaire
                        throw new \Exception('Erreur lors du téléchargement d\'un fichier.');
                    }
                }
            }
    
            // Persister et sauvegarder l'entité
            $entityManager->persist($banquette);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_banquette_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->render('banquette/new.html.twig', [
            'banquette' => $banquette,
            'form' => $form,
        ]);
    }
    

    #[Route('/{id}', name: 'app_banquette_show', methods: ['GET'])]
    public function show(Banquette $banquette): Response
    {
        return $this->render('banquette/show.html.twig', [
            'banquette' => $banquette,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_banquette_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Banquette $banquette, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BanquetteType::class, $banquette);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_banquette_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('banquette/edit.html.twig', [
            'banquette' => $banquette,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_banquette_delete', methods: ['POST'])]
    public function delete(Request $request, Banquette $banquette, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$banquette->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($banquette);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_banquette_index', [], Response::HTTP_SEE_OTHER);
    }
}
