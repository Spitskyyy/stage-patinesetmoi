<?php

namespace App\Controller;

use App\Entity\Stores;
use App\Form\StoresType;
use App\Repository\StoresRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;


#[Route('/stores')]
final class StoresController extends AbstractController
{
    #[Route(name: 'app_stores_index', methods: ['GET'])]
    public function index(StoresRepository $storesRepository): Response
    {
        return $this->render('stores/index.html.twig', [
            'stores' => $storesRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_stores_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
{
    $store = new Stores();
    $form = $this->createForm(StoresType::class, $store);
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
            $store->setPicture($newFilename); // Assure-toi que l'entité a une méthode setPicture()
        }

        // Persister et sauvegarder l'entité
        $entityManager->persist($store);
        $entityManager->flush();

        return $this->redirectToRoute('app_stores_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('stores/new.html.twig', [
        'store' => $store,
        'form' => $form,
    ]);
}


    #[Route('/{id}', name: 'app_stores_show', methods: ['GET'])]
    public function show(Stores $store): Response
    {
        return $this->render('stores/show.html.twig', [
            'store' => $store,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_stores_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Stores $store, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(StoresType::class, $store);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_stores_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('stores/edit.html.twig', [
            'store' => $store,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_stores_delete', methods: ['POST'])]
    public function delete(Request $request, Stores $store, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$store->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($store);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_stores_index', [], Response::HTTP_SEE_OTHER);
    }
}
