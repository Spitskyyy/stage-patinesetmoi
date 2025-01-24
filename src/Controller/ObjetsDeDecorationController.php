<?php

namespace App\Controller;

use App\Entity\ObjetsDeDecoration;
use App\Form\ObjetsDeDecorationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ObjetsDeDecorationRepository;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/objets_de_decoration')]
final class ObjetsDeDecorationController extends AbstractController
{
    #[Route(name: 'app_objets_de_decoration_index', methods: ['GET'])]
    public function index(ObjetsDeDecorationRepository $objetsDeDecorationRepository): Response
    {
        return $this->render('objets_de_decoration/index.html.twig', [
            'objets_de_decorations' => $objetsDeDecorationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_objets_de_decoration_new', methods: ['GET', 'POST'])]
    #[Route('/new', name: 'app_objets_de_decoration_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
{
    $objetsDeDecoration = new ObjetsDeDecoration();
    $form = $this->createForm(ObjetsDeDecorationType::class, $objetsDeDecoration);
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
            $objetsDeDecoration->setPicture($newFilename); // Assure-toi que l'entité a une méthode setPicture()
        }

        // Persister et sauvegarder l'entité
        $entityManager->persist($objetsDeDecoration);
        $entityManager->flush();

        return $this->redirectToRoute('app_objets_de_decoration_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('objets_de_decoration/new.html.twig', [
        'objets_de_decoration' => $objetsDeDecoration,
        'form' => $form,
    ]);
}


    #[Route('/{id}', name: 'app_objets_de_decoration_show', methods: ['GET'])]
    public function show(ObjetsDeDecoration $objetsDeDecoration): Response
    {
        return $this->render('objets_de_decoration/show.html.twig', [
            'objets_de_decoration' => $objetsDeDecoration,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_objets_de_decoration_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ObjetsDeDecoration $objetsDeDecoration, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ObjetsDeDecorationType::class, $objetsDeDecoration);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_objets_de_decoration_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('objets_de_decoration/edit.html.twig', [
            'objets_de_decoration' => $objetsDeDecoration,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_objets_de_decoration_delete', methods: ['POST'])]
    public function delete(Request $request, ObjetsDeDecoration $objetsDeDecoration, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$objetsDeDecoration->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($objetsDeDecoration);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_objets_de_decoration_index', [], Response::HTTP_SEE_OTHER);
    }
}
