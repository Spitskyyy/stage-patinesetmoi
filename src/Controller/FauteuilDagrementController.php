<?php

namespace App\Controller;

use App\Entity\FauteuilDagrement;
use App\Form\FauteuilDagrementType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\FauteuilDagrementRepository;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/fauteuil_dagrement')]
final class FauteuilDagrementController extends AbstractController
{
    #[Route(name: 'app_fauteuil_dagrement_index', methods: ['GET'])]
    public function index(Request $request, FauteuilDagrementRepository $fauteuilDagrementRepository): Response
    {
        $limit = 6;
    
        $page = max(1, $request->query->getInt('page', 1));
    
        $totalItems = $fauteuilDagrementRepository->count([]);
        
        $totalPages = max(1, ceil($totalItems / $limit));
    
        $page = min($page, $totalPages);
    
        $offset = ($page - 1) * $limit;
    
        $fauteuil_dagrement = $fauteuilDagrementRepository->createQueryBuilder('a')
            ->orderBy('a.title', 'ASC')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    
        
        return $this->render('fauteuil_dagrement/index.html.twig', [
            'fauteuil_dagrement' => $fauteuil_dagrement,
            'currentPage' => $page,
            'totalPages' => $totalPages,
        ]);
    }



    #[Route('/new', name: 'app_fauteuil_dagrement_new', methods: ['GET', 'POST'])]

public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
{
    $fauteuilDagrement = new FauteuilDagrement(); // Le tableau $pictures est initialisé dans l'entité

    $form = $this->createForm(FauteuilDagrementType::class, $fauteuilDagrement);
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
                    $fauteuilDagrement->addPicture($newFilename);
                } catch (FileException $e) {
                    // Gestion des erreurs si nécessaire
                    throw new \Exception('Erreur lors du téléchargement d\'un fichier.');
                }
            }
        }

        // Persister et sauvegarder l'entité
        $entityManager->persist($fauteuilDagrement);
        $entityManager->flush();

        return $this->redirectToRoute('app_fauteuil_dagrement_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('fauteuil_dagrement/new.html.twig', [
        'fauteuil_dagrement' => $fauteuilDagrement,
        'form' => $form,
    ]);
}

    #[Route('/{id}', name: 'app_fauteuil_dagrement_show', methods: ['GET'])]
    public function show(FauteuilDagrement $fauteuilDagrement): Response
    {
        return $this->render('fauteuil_dagrement/show.html.twig', [
            'fauteuil_dagrement' => $fauteuilDagrement,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_fauteuil_dagrement_edit', methods: ['GET', 'POST'])]

    public function edit(Request $request, FauteuilDagrement $fauteuilDagrement, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(FauteuilDagrementType::class, $fauteuilDagrement);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $deletePictures = $request->request->all('delete_pictures');
    
            if (!empty($deletePictures)) {
                $picturesArray = $fauteuilDagrement->getPictures();
                
                foreach ($deletePictures as $pictureToDelete) {
                    $filePath = $this->getParameter('pictures_directory') . '/' . $pictureToDelete;
                    
                    if (file_exists($filePath)) {
                        unlink($filePath); 
                    }
                    
                    $picturesArray = array_diff($picturesArray, [$pictureToDelete]);
                }
                
                $fauteuilDagrement->setPictures(array_values($picturesArray)); 
            }
    
            $newPictures = $form->get('pictures')->getData();
            if ($newPictures) {
                $picturesArray = $fauteuilDagrement->getPictures();
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
    
                $fauteuilDagrement->setPictures($picturesArray);
            }
    
            $entityManager->flush();
    
            return $this->redirectToRoute('app_fauteuil_dagrement_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->render('fauteuil_dagrement/edit.html.twig', [
            'fauteuil_dagrement' => $fauteuilDagrement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_fauteuil_dagrement_delete', methods: ['POST'])]

    public function delete(Request $request, FauteuilDagrement $fauteuilDagrement, EntityManagerInterface $entityManager): Response
{
    if ($this->isCsrfTokenValid('delete' . $fauteuilDagrement->getId(), $request->request->get('_token'))) {
        // Supprimer les images associées
        $pictures = $fauteuilDagrement->getPictures();

        if (!empty($pictures)) {
            foreach ($pictures as $picture) {
                $filePath = $this->getParameter('pictures_directory') . '/' . $picture;
                
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }
        }

        // Supprimer l'entité de la base de données
        $entityManager->remove($fauteuilDagrement);
        $entityManager->flush();
    }

    return $this->redirectToRoute('app_fauteuil_dagrement_index', [], Response::HTTP_SEE_OTHER);
}
}
