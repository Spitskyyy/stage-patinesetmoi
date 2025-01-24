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
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/fauteuil_dagrement')]
final class FauteuilDagrementController extends AbstractController
{
    #[Route(name: 'app_fauteuil_dagrement_index', methods: ['GET'])]
    public function index(FauteuilDagrementRepository $fauteuilDagrementRepository): Response
    {
        return $this->render('fauteuil_dagrement/index.html.twig', [
            'fauteuil_dagrements' => $fauteuilDagrementRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_fauteuil_dagrement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
{
    $fauteuilDagrement = new FauteuilDagrement();
    $form = $this->createForm(FauteuilDagrementType::class, $fauteuilDagrement);
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
            $fauteuilDagrement->setPicture($newFilename); // Assure-toi que l'entité a une méthode setPicture()
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
    public function edit(Request $request, FauteuilDagrement $fauteuilDagrement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FauteuilDagrementType::class, $fauteuilDagrement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
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
        if ($this->isCsrfTokenValid('delete'.$fauteuilDagrement->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($fauteuilDagrement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_fauteuil_dagrement_index', [], Response::HTTP_SEE_OTHER);
    }
}
