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
    public function index(Request $request, TringlerieRepository $tringlerieRepository): Response
    {
        // Nombre d'éléments par page
        $limit = 6;
    
        // Page actuelle, récupérée via le paramètre 'page' dans l'URL, par défaut 1
        $page = $request->query->getInt('page', 1);
    
        // Calcul de l'offset (la ligne de départ pour la requête)
        $offset = ($page - 1) * $limit;
    
        // Récupérer les éléments de la page actuelle
        $tringleries = $tringlerieRepository->createQueryBuilder('a')
            ->orderBy('a.title', 'ASC') // Tri par titre (ordre croissant)
            ->setFirstResult($offset)  // Définir l'offset
            ->setMaxResults($limit)   // Limiter le nombre d'éléments par page
            ->getQuery()
            ->getResult();
    
       
        $totalItems = count($tringlerieRepository->findAll()); 
    
        
        $totalPages = ceil($totalItems / $limit);
    
        
        return $this->render('tringlerie/index.html.twig', [
            'tringlerie' => $tringleries,
            'currentPage' => $page,
            'totalPages' => $totalPages,
        ]);
    }




    #[Route('/new', name: 'app_tringlerie_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $tringlerie = new Tringlerie();
    
        $form = $this->createForm(TringlerieType::class, $tringlerie);
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
                        $tringlerie->addPicture($newFilename);
                    } catch (FileException $e) {
                        // Gestion des erreurs si nécessaire
                        throw new \Exception('Erreur lors du téléchargement d\'un fichier.');
                    }
                }
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
    public function edit(Request $request, Tringlerie $tringlerie, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(TringlerieType::class, $tringlerie);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $deletePictures = $request->request->all('delete_pictures');
    
            if (!empty($deletePictures)) {
                $picturesArray = $tringlerie->getPictures();
                
                foreach ($deletePictures as $pictureToDelete) {
                    $filePath = $this->getParameter('pictures_directory') . '/' . $pictureToDelete;
                    
                    if (file_exists($filePath)) {
                        unlink($filePath); 
                    }
                    
                    $picturesArray = array_diff($picturesArray, [$pictureToDelete]);
                }
                
                $tringlerie->setPictures(array_values($picturesArray)); 
            }
    
            $newPictures = $form->get('pictures')->getData();
            if ($newPictures) {
                $picturesArray = $tringlerie->getPictures();
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
    
                $tringlerie->setPictures($picturesArray);
            }
    
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
        if ($this->isCsrfTokenValid('delete' . $tringlerie->getId(), $request->request->get('_token'))) {
            // Supprimer les images associées
            $pictures = $tringlerie->getPictures();
    
            if (!empty($pictures)) {
                foreach ($pictures as $picture) {
                    $filePath = $this->getParameter('pictures_directory') . '/' . $picture;
                    
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                }
            }
    
            // Supprimer l'entité de la base de données
            $entityManager->remove($tringlerie);
            $entityManager->flush();
        }
    
        return $this->redirectToRoute('app_tringlerie_index', [], Response::HTTP_SEE_OTHER);
    }
}
