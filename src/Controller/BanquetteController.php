<?php

namespace App\Controller;

use App\Entity\Banquette;
use App\Form\BanquetteType;
use App\Repository\BanquetteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/banquette')]
final class BanquetteController extends AbstractController
{
    #[Route(name: 'app_banquette_index', methods: ['GET'])]
    public function index(Request $request, BanquetteRepository $banquetteRepository): Response
    {
        $limit = 6;
    
        $page = max(1, $request->query->getInt('page', 1));
    
        $totalItems = $banquetteRepository->count([]);
        
        $totalPages = max(1, ceil($totalItems / $limit));
    
        $page = min($page, $totalPages);
    
        $offset = ($page - 1) * $limit;
    
        $banquettes = $banquetteRepository->createQueryBuilder('a')
            ->orderBy('a.title', 'ASC')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    
        
        return $this->render('banquette/index.html.twig', [
            'banquette' => $banquettes,
            'currentPage' => $page,
            'totalPages' => $totalPages,
        ]);
    }

    #[Route('/new', name: 'app_banquette_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
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
    #[IsGranted('ROLE_ADMIN')]
    public function edit(Request $request, Banquette $banquette, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
{
    $form = $this->createForm(BanquetteType::class, $banquette);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $deletePictures = $request->request->all('delete_pictures');

        if (!empty($deletePictures)) {
            $picturesArray = $banquette->getPictures();
            
            foreach ($deletePictures as $pictureToDelete) {
                $filePath = $this->getParameter('pictures_directory') . '/' . $pictureToDelete;
                
                if (file_exists($filePath)) {
                    unlink($filePath); 
                }
                
                $picturesArray = array_diff($picturesArray, [$pictureToDelete]);
            }
            
            $banquette->setPictures(array_values($picturesArray)); 
        }

        $newPictures = $form->get('pictures')->getData();
        if ($newPictures) {
            $picturesArray = $banquette->getPictures();
            foreach ($newPictures as $newPicture) {
                $originalFilename = pathinfo($newPicture->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $newPicture->guessExtension();

                try {
                    $newPicture->move(
                        $this->getParameter('pictures_directory'),
                        $newFilename
                    );
                    $picturesArray[] = $newFilename;
                } catch (FileException $e) {
                }
            }

            $banquette->setPictures($picturesArray);
        }

        $entityManager->flush();

        return $this->redirectToRoute('app_banquette_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('banquette/edit.html.twig', [
        'banquette' => $banquette,
        'form' => $form,
    ]);
}
    
    #[Route('/{id}', name: 'app_banquette_delete', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Request $request, Banquette $banquette, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $banquette->getId(), $request->request->get('_token'))) {
            // Supprimer les images associées
            $pictures = $banquette->getPictures();
    
            if (!empty($pictures)) {
                foreach ($pictures as $picture) {
                    $filePath = $this->getParameter('pictures_directory') . '/' . $picture;
                    
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                }
            }
    
            // Supprimer l'entité de la base de données
            $entityManager->remove($banquette);
            $entityManager->flush();
        }
    
        return $this->redirectToRoute('app_banquette_index', [], Response::HTTP_SEE_OTHER);
    }
}
