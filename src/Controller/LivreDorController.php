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
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/livre_dor')]
final class LivreDorController extends AbstractController
{
    #[Route(name: 'app_livre_dor_index', methods: ['GET'])]
    public function index(LivreDorRepository $livreDorRepository): Response
    {
        return $this->render('livre_dor/index.html.twig', [
            'livre_dors' => $livreDorRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_livre_dor_new', methods: ['GET', 'POST'])]
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
    public function edit(Request $request, LivreDor $livreDor, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LivreDorType::class, $livreDor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_livre_dor_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('livre_dor/edit.html.twig', [
            'livre_dor' => $livreDor,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_livre_dor_delete', methods: ['POST'])]
    public function delete(Request $request, LivreDor $livreDor, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$livreDor->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($livreDor);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_livre_dor_index', [], Response::HTTP_SEE_OTHER);
    }
}
