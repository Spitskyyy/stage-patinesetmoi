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
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/dessus/de/lit')]
final class DessusDeLitController extends AbstractController
{
    #[Route(name: 'app_dessus_de_lit_index', methods: ['GET'])]
    public function index(DessusDeLitRepository $dessusDeLitRepository): Response
    {
        return $this->render('dessus_de_lit/index.html.twig', [
            'dessus_de_lits' => $dessusDeLitRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_dessus_de_lit_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
{
    $dessusDeLit = new DessusDeLit();
    $form = $this->createForm(DessusDeLitType::class, $dessusDeLit);
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
            $dessusDeLit->setPicture($newFilename); // Assure-toi que l'entité a une méthode setPicture()
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
    public function edit(Request $request, DessusDeLit $dessusDeLit, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DessusDeLitType::class, $dessusDeLit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
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
        if ($this->isCsrfTokenValid('delete'.$dessusDeLit->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($dessusDeLit);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_dessus_de_lit_index', [], Response::HTTP_SEE_OTHER);
    }
}
