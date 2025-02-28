<?php

namespace App\Controller;

use App\Entity\DessusDeLit;
use App\Form\DessusDeLitType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\DessusDeLitRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/dessus_de_lit')]
final class DessusDeLitController extends AbstractController
{
    #[Route(name: 'app_dessus_de_lit_index', methods: ['GET'])]
    public function index(Request $request, DessusDeLitRepository $dessus_de_litRepository): Response
{
    $limit = 6;
    
    $page = max(1, $request->query->getInt('page', 1));

    $totalItems = $dessus_de_litRepository->count([]);
    
    $totalPages = max(1, ceil($totalItems / $limit));

    $page = min($page, $totalPages);

    $offset = ($page - 1) * $limit;

    $dessus_de_lit = $dessus_de_litRepository->createQueryBuilder('a')
        ->orderBy('a.title', 'ASC')
        ->setFirstResult($offset)
        ->setMaxResults($limit)
        ->getQuery()
        ->getResult();

    
    return $this->render('dessus_de_lit/index.html.twig', [
        'dessus_de_lit' => $dessus_de_lit,
        'currentPage' => $page,
        'totalPages' => $totalPages,
    ]);
}


    #[Route('/new', name: 'app_dessus_de_lit_new', methods: ['GET', 'POST'])]

    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $dessusDeLit = new DessusDeLit(); // Le tableau $pictures est initialisé dans l'entité
    
        $form = $this->createForm(DessusDeLitType::class, $dessusDeLit);
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
                        $dessusDeLit->addPicture($newFilename);
                    } catch (FileException $e) {
                        // Gestion des erreurs si nécessaire
                        throw new \Exception('Erreur lors du téléchargement d\'un fichier.');
                    }
                }
            }
    
            // Persister et sauvegarder l'entité
            $entityManager->persist($dessusDeLit);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_dessus_de_lit_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->render('dessus_de_lit/new.html.twig', [
            'dessus_de_lit' => $dessusDeLit,
            'form' => $form,
        ]);
    }
    


    #[Route('/{id}', name: 'app_dessus_de_lit_show', methods: ['GET'])]
    public function show(DessusDeLit $dessusDeLit): Response
    {
        return $this->render('dessus_de_lit/show.html.twig', [
            'dessus_de_lit' => $dessusDeLit,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_dessus_de_lit_edit', methods: ['GET', 'POST'])]

    public function edit(Request $request, DessusDeLit $dessusDeLit, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
{
    $form = $this->createForm(DessusDeLitType::class, $dessusDeLit);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $deletePictures = $request->request->all('delete_pictures');

        if (!empty($deletePictures)) {
            $picturesArray = $dessusDeLit->getPictures();
            
            foreach ($deletePictures as $pictureToDelete) {
                $filePath = $this->getParameter('pictures_directory') . '/' . $pictureToDelete;
                
                if (file_exists($filePath)) {
                    unlink($filePath); 
                }
                
                $picturesArray = array_diff($picturesArray, [$pictureToDelete]);
            }
            
            $dessusDeLit->setPictures(array_values($picturesArray)); 
        }

        $newPictures = $form->get('pictures')->getData();
        if ($newPictures) {
            $picturesArray = $dessusDeLit->getPictures();
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

            $dessusDeLit->setPictures($picturesArray);
        }

        $entityManager->flush();

        return $this->redirectToRoute('app_dessus_de_lit_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('dessus_de_lit/edit.html.twig', [
        'dessus_de_lit' => $dessusDeLit,
        'form' => $form,
    ]);
}

    #[Route('/{id}', name: 'app_dessus_de_lit_delete', methods: ['POST'])]

    public function delete(Request $request, DessusDeLit $dessusDeLit, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $dessusDeLit->getId(), $request->request->get('_token'))) {
            // Supprimer les images associées
            $pictures = $dessusDeLit->getPictures();
    
            if (!empty($pictures)) {
                foreach ($pictures as $picture) {
                    $filePath = $this->getParameter('pictures_directory') . '/' . $picture;
                    
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                }
            }
    
            // Supprimer l'entité de la base de données
            $entityManager->remove($dessusDeLit);
            $entityManager->flush();
        }
    
        return $this->redirectToRoute('app_dessus_de_lit_index', [], Response::HTTP_SEE_OTHER);
    }
}
