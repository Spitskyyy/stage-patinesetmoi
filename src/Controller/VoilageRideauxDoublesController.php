<?php

namespace App\Controller;

use App\Entity\VoilageRideauxDoubles;
use App\Form\VoilageRideauxDoublesType;
use App\Repository\VoilageRideauxDoublesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/voilage_rideaux_doubles')]
final class VoilageRideauxDoublesController extends AbstractController
{
    #[Route(name: 'app_voilage_rideaux_doubles_index', methods: ['GET'])]
    public function index(VoilageRideauxDoublesRepository $voilageRideauxDoublesRepository): Response
    {
        return $this->render('voilage_rideaux_doubles/index.html.twig', [
            'voilage_rideaux_doubles' => $voilageRideauxDoublesRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_voilage_rideaux_doubles_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
{
    $voilageRideauxDouble = new VoilageRideauxDoubles();
    $form = $this->createForm(VoilageRideauxDoublesType::class, $voilageRideauxDouble);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Gestion de l'upload de l'image
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
            $voilageRideauxDouble->setPicture($newFilename); // Assure-toi que l'entité a une méthode setPicture()
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
    public function edit(Request $request, VoilageRideauxDoubles $voilageRideauxDouble, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(VoilageRideauxDoublesType::class, $voilageRideauxDouble);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_voilage_rideaux_doubles_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('voilage_rideaux_doubles/edit.html.twig', [
            'voilage_rideaux_double' => $voilageRideauxDouble,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_voilage_rideaux_doubles_delete', methods: ['POST'])]
    public function delete(Request $request, VoilageRideauxDoubles $voilageRideauxDouble, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$voilageRideauxDouble->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($voilageRideauxDouble);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_voilage_rideaux_doubles_index', [], Response::HTTP_SEE_OTHER);
    }
}
