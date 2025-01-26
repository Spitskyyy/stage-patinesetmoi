<?php
namespace App\Controller;

use App\Entity\MisesEnScene;
use App\Form\MisesEnSceneType;
use App\Repository\MisesEnSceneRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/mises_en_scene')]
final class MisesEnSceneController extends AbstractController
{
    #[Route(name: 'app_mises_en_scene_index', methods: ['GET'])]
    public function index(MisesEnSceneRepository $misesEnSceneRepository): Response
    {
        return $this->render('mises_en_scene/index.html.twig', [
            'mises_en_scenes' => $misesEnSceneRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_mises_en_scene_new', methods: ['GET', 'POST'])]
    public function new (Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $misesEnScene = new MisesEnScene();
        $form         = $this->createForm(MisesEnSceneType::class, $misesEnScene);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer le fichier de l'image depuis le formulaire (champ "picture")
            $pictureFile = $form->get('picture')->getData();

            if ($pictureFile) {
                // Générer un nom de fichier unique et sûr
                $originalFilename = pathinfo($pictureFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename     = $slugger->slug($originalFilename);
                $newFilename      = $safeFilename . '-' . uniqid() . '.' . $pictureFile->guessExtension();

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
                $misesEnScene->setPicture($newFilename); // Assure-toi que l'entité a une méthode setPicture()
            }

            // Persister et sauvegarder l'entité
            $entityManager->persist($misesEnScene);
            $entityManager->flush();

            return $this->redirectToRoute('app_mises_en_scene_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('mises_en_scene/new.html.twig', [
            'mises_en_scene' => $misesEnScene,
            'form'           => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_mises_en_scene_show', methods: ['GET'])]
    public function show(MisesEnScene $misesEnScene): Response
    {
        return $this->render('mises_en_scene/show.html.twig', [
            'mises_en_scene' => $misesEnScene,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_mises_en_scene_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, MisesEnScene $misesEnScene, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MisesEnSceneType::class, $misesEnScene);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_mises_en_scene_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('mises_en_scene/edit.html.twig', [
            'mises_en_scene' => $misesEnScene,
            'form'           => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_mises_en_scene_delete', methods: ['POST'])]
    public function delete(Request $request, MisesEnScene $misesEnScene, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $misesEnScene->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($misesEnScene);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_mises_en_scene_index', [], Response::HTTP_SEE_OTHER);
    }
}
