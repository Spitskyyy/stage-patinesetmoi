<?php

namespace App\Controller;

use App\Entity\Garniture;
use App\Form\GarnitureType;
use App\Repository\GarnitureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/garniture')]
final class GarnitureController extends AbstractController
{
    #[Route(name: 'app_garniture_index', methods: ['GET'])]
    public function index(GarnitureRepository $garnitureRepository): Response
    {
        return $this->render('garniture/index.html.twig', [
            'garnitures' => $garnitureRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_garniture_new', methods: ['GET', 'POST'])]
    #[Route('/new', name: 'app_garniture_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
{
    $garniture = new Garniture();
    $form = $this->createForm(GarnitureType::class, $garniture);
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
            $garniture->setPicture($newFilename); // Assure-toi que l'entité a une méthode setPicture()
        }

        // Persister et sauvegarder l'entité
        $entityManager->persist($garniture);
        $entityManager->flush();

        return $this->redirectToRoute('app_garniture_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('garniture/new.html.twig', [
        'garniture' => $garniture,
        'form' => $form,
    ]);
}


    #[Route('/{id}', name: 'app_garniture_show', methods: ['GET'])]
    public function show(Garniture $garniture): Response
    {
        return $this->render('garniture/show.html.twig', [
            'garniture' => $garniture,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_garniture_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Garniture $garniture, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(GarnitureType::class, $garniture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_garniture_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('garniture/edit.html.twig', [
            'garniture' => $garniture,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_garniture_delete', methods: ['POST'])]
    public function delete(Request $request, Garniture $garniture, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$garniture->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($garniture);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_garniture_index', [], Response::HTTP_SEE_OTHER);
    }
}
