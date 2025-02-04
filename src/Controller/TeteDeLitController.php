<?php

namespace App\Controller;

use App\Entity\TeteDeLit;
use App\Form\TeteDeLitType;
use App\Repository\TeteDeLitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/tete_de_lit')]
final class TeteDeLitController extends AbstractController
{
    #[Route(name: 'app_tete_de_lit_index', methods: ['GET'])]
    public function index(Request $request, TeteDeLitRepository $teteDeLitRepository): Response
    {
        // Nombre d'éléments par page
        $limit = 6;
    
        // Page actuelle, récupérée via le paramètre 'page' dans l'URL, par défaut 1
        $page = $request->query->getInt('page', 1);
    
        // Calcul de l'offset (la ligne de départ pour la requête)
        $offset = ($page - 1) * $limit;
    
        // Récupérer les éléments de la page actuelle
        $tete_de_lits = $teteDeLitRepository->createQueryBuilder('a')
            ->orderBy('a.title', 'ASC') // Tri par titre (ordre croissant)
            ->setFirstResult($offset)  // Définir l'offset
            ->setMaxResults($limit)   // Limiter le nombre d'éléments par page
            ->getQuery()
            ->getResult();
    
       
        $totalItems = count($teteDeLitRepository->findAll()); 
    
        
        $totalPages = ceil($totalItems / $limit);
    
        
        return $this->render('tete_de_lit/index.html.twig', [
            'tete_de_lit' => $tete_de_lits,
            'currentPage' => $page,
            'totalPages' => $totalPages,
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

    public function edit(Request $request, TeteDeLit $teteDeLit, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(TeteDeLitType::class, $teteDeLit);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $deletePictures = $request->request->all('delete_pictures');
    
            if (!empty($deletePictures)) {
                $picturesArray = $teteDeLit->getPictures();
                
                foreach ($deletePictures as $pictureToDelete) {
                    $filePath = $this->getParameter('pictures_directory') . '/' . $pictureToDelete;
                    
                    if (file_exists($filePath)) {
                        unlink($filePath); 
                    }
                    
                    $picturesArray = array_diff($picturesArray, [$pictureToDelete]);
                }
                
                $teteDeLit->setPictures(array_values($picturesArray)); 
            }
    
            $newPictures = $form->get('pictures')->getData();
            if ($newPictures) {
                $picturesArray = $teteDeLit->getPictures();
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
    
                $teteDeLit->setPictures($picturesArray);
            }
    
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
        if ($this->isCsrfTokenValid('delete' . $teteDeLit->getId(), $request->request->get('_token'))) {
            // Supprimer les images associées
            $pictures = $teteDeLit->getPictures();
    
            if (!empty($pictures)) {
                foreach ($pictures as $picture) {
                    $filePath = $this->getParameter('pictures_directory') . '/' . $picture;
                    
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                }
            }
    
            // Supprimer l'entité de la base de données
            $entityManager->remove($teteDeLit);
            $entityManager->flush();
        }
    
        return $this->redirectToRoute('app_tete_de_lit_index', [], Response::HTTP_SEE_OTHER);
    }
}
