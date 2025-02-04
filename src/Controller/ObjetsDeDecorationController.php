<?php

namespace App\Controller;

use App\Entity\ObjetsDeDecoration;
use App\Form\ObjetsDeDecorationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ObjetsDeDecorationRepository;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/objets_de_decoration')]
final class ObjetsDeDecorationController extends AbstractController
{
    #[Route(name: 'app_objets_de_decoration_index', methods: ['GET'])]
    public function index(Request $request, ObjetsDeDecorationRepository $objetsDeDecorationRepository): Response
    {
        $limit = 6;
    
        $page = max(1, $request->query->getInt('page', 1));
    
        $totalItems = $objetsDeDecorationRepository->count([]);
        
        $totalPages = max(1, ceil($totalItems / $limit));
    
        $page = min($page, $totalPages);
    
        $offset = ($page - 1) * $limit;
    
        $objets_de_decoration = $objetsDeDecorationRepository->createQueryBuilder('a')
            ->orderBy('a.title', 'ASC')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    
        return $this->render('objets_de_decoration/index.html.twig', [
            'objets_de_decoration' => $objets_de_decoration,
            'currentPage' => $page,
            'totalPages' => $totalPages,
        ]);
    }

    #[Route('/new', name: 'app_objets_de_decoration_new', methods: ['GET', 'POST'])]

    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $objetsDeDecoration = new ObjetsDeDecoration(); // Le tableau $pictures est initialisé dans l'entité
    
        $form = $this->createForm(ObjetsDeDecorationType::class, $objetsDeDecoration);
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
                        $objetsDeDecoration->addPicture($newFilename);
                    } catch (FileException $e) {
                        // Gestion des erreurs si nécessaire
                        throw new \Exception('Erreur lors du téléchargement d\'un fichier.');
                    }
                }
            }
    
            // Persister et sauvegarder l'entité
            $entityManager->persist($objetsDeDecoration);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_objets_de_decoration_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->render('objets_de_decoration/new.html.twig', [
            'objets_de_decoration' => $objetsDeDecoration,
            'form' => $form,
        ]);
    }
    


    #[Route('/{id}', name: 'app_objets_de_decoration_show', methods: ['GET'])]
    public function show(ObjetsDeDecoration $objetsDeDecoration): Response
    {
        return $this->render('objets_de_decoration/show.html.twig', [
            'objets_de_decoration' => $objetsDeDecoration,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_objets_de_decoration_edit', methods: ['GET', 'POST'])]

    public function edit(Request $request, ObjetsDeDecoration $objetsDeDecoration, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(ObjetsDeDecorationType::class, $objetsDeDecoration);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $deletePictures = $request->request->all('delete_pictures');
    
            if (!empty($deletePictures)) {
                $picturesArray = $objetsDeDecoration->getPictures();
                
                foreach ($deletePictures as $pictureToDelete) {
                    $filePath = $this->getParameter('pictures_directory') . '/' . $pictureToDelete;
                    
                    if (file_exists($filePath)) {
                        unlink($filePath); 
                    }
                    
                    $picturesArray = array_diff($picturesArray, [$pictureToDelete]);
                }
                
                $objetsDeDecoration->setPictures(array_values($picturesArray)); 
            }
    
            $newPictures = $form->get('pictures')->getData();
            if ($newPictures) {
                $picturesArray = $objetsDeDecoration->getPictures();
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
    
                $objetsDeDecoration->setPictures($picturesArray);
            }
    
            $entityManager->flush();
    
            return $this->redirectToRoute('app_objets_de_decoration_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->render('objets_de_decoration/edit.html.twig', [
            'objets_de_decoration' => $objetsDeDecoration,
            'form' => $form,
        ]);
    }



    #[Route('/{id}', name: 'app_objets_de_decoration_delete', methods: ['POST'])]

    public function delete(Request $request, ObjetsDeDecoration $objetsDeDecoration, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $objetsDeDecoration->getId(), $request->request->get('_token'))) {
            // Supprimer les images associées
            $pictures = $objetsDeDecoration->getPictures();
    
            if (!empty($pictures)) {
                foreach ($pictures as $picture) {
                    $filePath = $this->getParameter('pictures_directory') . '/' . $picture;
                    
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                }
            }
    
            // Supprimer l'entité de la base de données
            $entityManager->remove($objetsDeDecoration);
            $entityManager->flush();
        }
    
        return $this->redirectToRoute('app_objets_de_decoration_index', [], Response::HTTP_SEE_OTHER);
    }
}
