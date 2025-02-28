<?php

namespace App\Controller;

use App\Entity\AvantApres;
use App\Form\AvantApresType;
use App\Repository\AvantApresRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/avant_apres')]
final class AvantApresController extends AbstractController
{
    #[Route(name: 'app_avant_apres_index', methods: ['GET'])]
    
    public function index(Request $request, AvantApresRepository $avantApresRepository): Response
    {
        $limit = 6;
    
        $page = max(1, $request->query->getInt('page', 1));
    
        $totalItems = $avantApresRepository->count([]);
        
        $totalPages = max(1, ceil($totalItems / $limit));
    
        $page = min($page, $totalPages);
    
        $offset = ($page - 1) * $limit;
    
        $avantApres = $avantApresRepository->createQueryBuilder('a')
            ->orderBy('a.title', 'ASC')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    
        
        return $this->render('avant_apres/index.html.twig', [
            'avant_apres' => $avantApres,
            'currentPage' => $page,
            'totalPages' => $totalPages,
        ]);
    }


    #[Route('/new', name: 'app_avant_apres_new', methods: ['GET', 'POST'])]

    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $avantApre = new AvantApres(); // Le tableau $pictures est initialisé dans l'entité
    
        $form = $this->createForm(AvantApresType::class, $avantApre);
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
                        $avantApre->addPicture($newFilename);
                    } catch (FileException $e) {
                        // Gestion des erreurs si nécessaire
                        throw new \Exception('Erreur lors du téléchargement d\'un fichier.');
                    }
                }
            }
    
            // Persister et sauvegarder l'entité
            $entityManager->persist($avantApre);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_avant_apres_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->render('avant_apres/new.html.twig', [
            'avant_apre' => $avantApre,
            'form' => $form,
        ]);
    }
    


    #[Route('/{id}', name: 'app_avant_apres_show', methods: ['GET'])]
    public function show(AvantApres $avantApre): Response
    {
        return $this->render('avant_apres/show.html.twig', [
            'avant_apre' => $avantApre,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_avant_apres_edit', methods: ['GET', 'POST'])]

    public function edit(Request $request, AvantApres $avantApre, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(AvantApresType::class, $avantApre);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $deletePictures = $request->request->all('delete_pictures');
    
            if (!empty($deletePictures)) {
                $picturesArray = $avantApre->getPictures();
                
                foreach ($deletePictures as $pictureToDelete) {
                    $filePath = $this->getParameter('pictures_directory') . '/' . $pictureToDelete;
                    
                    if (file_exists($filePath)) {
                        unlink($filePath); 
                    }
                    
                    $picturesArray = array_diff($picturesArray, [$pictureToDelete]);
                }
                
                $avantApre->setPictures(array_values($picturesArray)); 
            }
    
            $newPictures = $form->get('pictures')->getData();
            if ($newPictures) {
                $picturesArray = $avantApre->getPictures();
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
    
                $avantApre->setPictures($picturesArray);
            }
    
            $entityManager->flush();
    
            return $this->redirectToRoute('app_abat_jour_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->render('abat_jour/edit.html.twig', [
            'avant_apre' => $avantApre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_avant_apres_delete', methods: ['POST'])]

    public function delete(Request $request, AvantApres $avantApre, EntityManagerInterface $entityManager): Response
{
    if ($this->isCsrfTokenValid('delete' . $avantApre->getId(), $request->request->get('_token'))) {
        // Supprimer les images associées
        $pictures = $avantApre->getPictures();

        if (!empty($pictures)) {
            foreach ($pictures as $picture) {
                $filePath = $this->getParameter('pictures_directory') . '/' . $picture;
                
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }
        }

        // Supprimer l'entité de la base de données
        $entityManager->remove($avantApre);
        $entityManager->flush();
    }

    return $this->redirectToRoute('app_avant_apres_index', [], Response::HTTP_SEE_OTHER);
}
}
