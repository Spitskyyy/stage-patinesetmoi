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
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/avant_apres')]
final class AvantApresController extends AbstractController
{
    #[Route(name: 'app_avant_apres_index', methods: ['GET'])]
    public function index(AvantApresRepository $avantApresRepository): Response
    {
        return $this->render('avant_apres/index.html.twig', [
            'avant_apres' => $avantApresRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_avant_apres_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
{
    $avantApre = new AvantApres();
    $form = $this->createForm(AvantApresType::class, $avantApre);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Récupérer le fichier de l'image depuis le formulaire (champ "picture")
        $pictureFile = $form->get('picture')->getData();

        if ($pictureFile) {
            // Générer un nom de fichier unique et sûr
            $originalFilename = pathinfo($pictureFile->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename . '-' . uniqid() . '.' . $pictureFile->guessExtension();

            try {
                // Déplacer le fichier dans le répertoire configuré
                $pictureFile->move(
                    $this->getParameter('images_directory'), // Paramètre défini dans config/services.yaml
                    $newFilename
                );
            } catch (FileException $e) {
                // Gestion des erreurs si le fichier ne peut pas être déplacé
                throw new \Exception('Erreur lors du téléchargement du fichier.');
            }

            // Enregistrer le nom du fichier dans l'entité
            $avantApre->setPicture($newFilename); // Assure-toi que l'entité a une méthode setPicture()
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
    public function edit(Request $request, AvantApres $avantApre, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AvantApresType::class, $avantApre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_avant_apres_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('avant_apres/edit.html.twig', [
            'avant_apre' => $avantApre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_avant_apres_delete', methods: ['POST'])]
    public function delete(Request $request, AvantApres $avantApre, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$avantApre->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($avantApre);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_avant_apres_index', [], Response::HTTP_SEE_OTHER);
    }
}
