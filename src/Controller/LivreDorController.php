<?php

namespace App\Controller;

use App\Entity\LivreDor;
use App\Form\LivreDorType;
use App\Repository\LivreDorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/livre_dor')]
final class LivreDorController extends AbstractController
{
    #[Route(name: 'app_livre_dor_index', methods: ['GET'])]


public function index(Request $request, LivreDorRepository $livreDorRepository): Response
    {
        // Nombre d'éléments par page
        $limit = 6;
    
        // Page actuelle, récupérée via le paramètre 'page' dans l'URL, par défaut 1
        $page = $request->query->getInt('page', 1);
    
        // Calcul de l'offset (la ligne de départ pour la requête)
        $offset = ($page - 1) * $limit;
    
        // Récupérer les éléments de la page actuelle
        $livre_dors = $livreDorRepository->createQueryBuilder('a')
            ->orderBy('a.title', 'ASC') // Tri par titre (ordre croissant)
            ->setFirstResult($offset)  // Définir l'offset
            ->setMaxResults($limit)   // Limiter le nombre d'éléments par page
            ->getQuery()
            ->getResult();
    
       
        $totalItems = count($livreDorRepository->findAll()); 
    
        
        $totalPages = ceil($totalItems / $limit);
    
        
        return $this->render('livre_dor/index.html.twig', [
            'livre_dor' => $livre_dors,
            'currentPage' => $page,
            'totalPages' => $totalPages,
        ]);
    }

    #[Route('/new', name: 'app_livre_dor_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
{
    $livreDor = new LivreDor(); // Le tableau $pictures est initialisé dans l'entité

    $form = $this->createForm(LivreDorType::class, $livreDor);
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
                    $livreDor->addPicture($newFilename);
                } catch (FileException $e) {
                    // Gestion des erreurs si nécessaire
                    throw new \Exception('Erreur lors du téléchargement d\'un fichier.');
                }
            }
        }

        // Persister et sauvegarder l'entité
        $entityManager->persist($livreDor);
        $entityManager->flush();

        return $this->redirectToRoute('app_livre_dor_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('livre_dor/new.html.twig', [
        'livre_dor' => $livreDor,
        'form' => $form,
    ]);
}

    #[Route('/{id}', name: 'app_livre_dor_show', methods: ['GET'])]
    public function show(LivreDor $livreDor): Response
    {
        return $this->render('livre_dor/show.html.twig', [
            'livre_dor' => $livreDor,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_livre_dor_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function edit(Request $request, LivreDor $livreDor, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(LivreDorType::class, $livreDor);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $deletePictures = $request->request->all('delete_pictures');
    
            if (!empty($deletePictures)) {
                $picturesArray = $livreDor->getPictures();
                
                foreach ($deletePictures as $pictureToDelete) {
                    $filePath = $this->getParameter('pictures_directory') . '/' . $pictureToDelete;
                    
                    if (file_exists($filePath)) {
                        unlink($filePath); 
                    }
                    
                    $picturesArray = array_diff($picturesArray, [$pictureToDelete]);
                }
                
                $livreDor->setPictures(array_values($picturesArray)); 
            }
    
            $newPictures = $form->get('pictures')->getData();
            if ($newPictures) {
                $picturesArray = $livreDor->getPictures();
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
    
                $livreDor->setPictures($picturesArray);
            }
    
            $entityManager->flush();
    
            return $this->redirectToRoute('app_livre_dor_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->render('livre_dor/edit.html.twig', [
            'livre_dor' => $livreDor,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_livre_dor_delete', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Request $request, LivreDor $livreDor, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $livreDor->getId(), $request->request->get('_token'))) {
            // Supprimer les images associées
            $pictures = $livreDor->getPictures();
    
            if (!empty($pictures)) {
                foreach ($pictures as $picture) {
                    $filePath = $this->getParameter('pictures_directory') . '/' . $picture;
                    
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                }
            }
    
            // Supprimer l'entité de la base de données
            $entityManager->remove($livreDor);
            $entityManager->flush();
        }
    
        return $this->redirectToRoute('app_livre_dor_index', [], Response::HTTP_SEE_OTHER);
    }

}
