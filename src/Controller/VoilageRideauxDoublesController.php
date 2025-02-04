<?php

namespace App\Controller;

use App\Entity\VoilageRideauxDoubles;
use App\Form\VoilageRideauxDoublesType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\VoilageRideauxDoublesRepository;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/voilage_rideaux_doubles')]
final class VoilageRideauxDoublesController extends AbstractController
{
    #[Route(name: 'app_voilage_rideaux_doubles_index', methods: ['GET'])]
    public function index(Request $request, VoilageRideauxDoublesRepository $voilageRideauxDoublesRepository): Response
    {
        $limit = 6;
    
        $page = max(1, $request->query->getInt('page', 1));
    
        $totalItems = $voilageRideauxDoublesRepository->count([]);
        
        $totalPages = max(1, ceil($totalItems / $limit));
    
        $page = min($page, $totalPages);
    
        $offset = ($page - 1) * $limit;
    
        $voilage_rideaux_doubless = $voilageRideauxDoublesRepository->createQueryBuilder('a')
            ->orderBy('a.title', 'ASC')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    
        return $this->render('voilage_rideaux_doubles/index.html.twig', [
            'voilage_rideaux_doubles' => $voilage_rideaux_doubless,
            'currentPage' => $page,
            'totalPages' => $totalPages,
        ]);
    }

    #[Route('/new', name: 'app_voilage_rideaux_doubles_new', methods: ['GET', 'POST'])]

    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $voilageRideauxDouble = new VoilageRideauxDoubles(); // Le tableau $pictures est initialisé dans l'entité
    
        $form = $this->createForm(VoilageRideauxDoublesType::class, $voilageRideauxDouble);
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
                        $voilageRideauxDouble->addPicture($newFilename);
                    } catch (FileException $e) {
                        // Gestion des erreurs si nécessaire
                        throw new \Exception('Erreur lors du téléchargement d\'un fichier.');
                    }
                }
            }
    
            // Persister et sauvegarder l'entité
            $entityManager->persist($voilageRideauxDouble);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_voilage_rideaux_doubles_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->render('voilage_rideaux_doubles/new.html.twig', [
            'voilage_rideaux_double' => $voilageRideauxDouble,
            'form' => $form,
        ]);
    }
    


    #[Route('/{id}', name: 'app_voilage_rideaux_doubles_show', methods: ['GET'])]
    public function show(VoilageRideauxDoubles $voilageRideauxDouble): Response
    {
        return $this->render('voilage_rideaux_doubles/show.html.twig', [
            'voilage_rideaux_double' => $voilageRideauxDouble,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_voilage_rideaux_doubles_edit', methods: ['GET', 'POST'])]

    public function edit(Request $request, VoilageRideauxDoubles $voilageRideauxDouble, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(VoilageRideauxDoublesType::class, $voilageRideauxDouble);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $deletePictures = $request->request->all('delete_pictures');
    
            if (!empty($deletePictures)) {
                $picturesArray = $voilageRideauxDouble->getPictures();
                
                foreach ($deletePictures as $pictureToDelete) {
                    $filePath = $this->getParameter('pictures_directory') . '/' . $pictureToDelete;
                    
                    if (file_exists($filePath)) {
                        unlink($filePath); 
                    }
                    
                    $picturesArray = array_diff($picturesArray, [$pictureToDelete]);
                }
                
                $voilageRideauxDouble->setPictures(array_values($picturesArray)); 
            }
    
            $newPictures = $form->get('pictures')->getData();
            if ($newPictures) {
                $picturesArray = $voilageRideauxDouble->getPictures();
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
    
                $voilageRideauxDouble->setPictures($picturesArray);
            }
    
            $entityManager->flush();
    
            return $this->redirectToRoute('app_voilage_rideaux_double_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->render('voilage_rideaux_double/edit.html.twig', [
            'voilage_rideaux_double' => $voilageRideauxDouble,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_voilage_rideaux_doubles_delete', methods: ['POST'])]

    public function delete(Request $request, VoilageRideauxDoubles $voilageRideauxDouble, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $voilageRideauxDouble->getId(), $request->request->get('_token'))) {
            // Supprimer les images associées
            $pictures = $voilageRideauxDouble->getPictures();
    
            if (!empty($pictures)) {
                foreach ($pictures as $picture) {
                    $filePath = $this->getParameter('pictures_directory') . '/' . $picture;
                    
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                }
            }
    
            // Supprimer l'entité de la base de données
            $entityManager->remove($voilageRideauxDouble);
            $entityManager->flush();
        }
    
        return $this->redirectToRoute('app_voilage_rideaux_doubles_index', [], Response::HTTP_SEE_OTHER);
    }
}
