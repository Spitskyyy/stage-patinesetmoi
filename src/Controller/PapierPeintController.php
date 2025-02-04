<?php

namespace App\Controller;

use App\Entity\PapierPeint;
use App\Form\PapierPeintType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\PapierPeintRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/papier/peint')]
final class PapierPeintController extends AbstractController
{
    #[Route(name: 'app_papier_peint_index', methods: ['GET'])]
    public function index(Request $request, PapierPeintRepository $papierPeintRepository): Response
    {
        $limit = 6;
    
        $page = max(1, $request->query->getInt('page', 1));
    
        $totalItems = $papierPeintRepository->count([]);
        
        $totalPages = max(1, ceil($totalItems / $limit));
    
        $page = min($page, $totalPages);
    
        $offset = ($page - 1) * $limit;
    
        $papierPeint = $papierPeintRepository->createQueryBuilder('a')
            ->orderBy('a.title', 'ASC')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    
        return $this->render('papierPeint/index.html.twig', [
            'papierPeint' => $papierPeint,
            'currentPage' => $page,
            'totalPages' => $totalPages,
        ]);
    }

    #[Route('/new', name: 'app_papier_peint_new', methods: ['GET', 'POST'])]


    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $papierPeint = new PapierPeint(); // Le tableau $pictures est initialisé dans l'entité
    
        $form = $this->createForm(PapierPeint::class, $papierPeint);
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
                        $papierPeint->addPicture($newFilename);
                    } catch (FileException $e) {
                        // Gestion des erreurs si nécessaire
                        throw new \Exception('Erreur lors du téléchargement d\'un fichier.');
                    }
                }
            }
    
            // Persister et sauvegarder l'entité
            $entityManager->persist($papierPeint);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_papier_peint_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->render('papier_peint/new.html.twig', [
            'papier_peint' => $papierPeint,
            'form' => $form,
        ]);
    }



    #[Route('/{id}', name: 'app_papier_peint_show', methods: ['GET'])]
    public function show(PapierPeint $papierPeint): Response
    {
        return $this->render('papier_peint/show.html.twig', [
            'papier_peint' => $papierPeint,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_papier_peint_edit', methods: ['GET', 'POST'])]


    public function edit(Request $request, PapierPeint $papierPeint, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(PapierPeintType::class, $papierPeint);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $deletePictures = $request->request->all('delete_pictures');
    
            if (!empty($deletePictures)) {
                $picturesArray = $papierPeint->getPictures();
                
                foreach ($deletePictures as $pictureToDelete) {
                    $filePath = $this->getParameter('pictures_directory') . '/' . $pictureToDelete;
                    
                    if (file_exists($filePath)) {
                        unlink($filePath); 
                    }
                    
                    $picturesArray = array_diff($picturesArray, [$pictureToDelete]);
                }
                
                $papierPeint->setPictures(array_values($picturesArray)); 
            }
    
            $newPictures = $form->get('pictures')->getData();
            if ($newPictures) {
                $picturesArray = $papierPeint->getPictures();
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
    
                $papierPeint->setPictures($picturesArray);
            }
    
            $entityManager->flush();
    
            return $this->redirectToRoute('app_papier_peint_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->render('papier_peint/edit.html.twig', [
            'papier_peint' => $papierPeint,
            'form' => $form,
        ]);
    }


    #[Route('/{id}', name: 'app_papier_peint_delete', methods: ['POST'])]


    public function delete(Request $request, PapierPeint $papierPeint, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $papierPeint->getId(), $request->request->get('_token'))) {
            // Supprimer les images associées
            $pictures = $papierPeint->getPictures();
    
            if (!empty($pictures)) {
                foreach ($pictures as $picture) {
                    $filePath = $this->getParameter('pictures_directory') . '/' . $picture;
                    
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                }
            }
    
            // Supprimer l'entité de la base de données
            $entityManager->remove($papierPeint);
            $entityManager->flush();
        }
    
        return $this->redirectToRoute('app_papier_peint_index', [], Response::HTTP_SEE_OTHER);
    }
}
