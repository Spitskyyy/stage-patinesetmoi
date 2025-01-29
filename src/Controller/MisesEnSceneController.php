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
    public function index(Request $request, MisesEnSceneRepository $misesEnSceneRepository): Response
    {
        // Nombre d'éléments par page
        $limit = 6;
    
        // Page actuelle, récupérée via le paramètre 'page' dans l'URL, par défaut 1
        $page = $request->query->getInt('page', 1);
    
        // Calcul de l'offset (la ligne de départ pour la requête)
        $offset = ($page - 1) * $limit;
    
        // Récupérer les éléments de la page actuelle
        $mises_en_scenes = $misesEnSceneRepository->createQueryBuilder('a')
            ->orderBy('a.title', 'ASC') // Tri par titre (ordre croissant)
            ->setFirstResult($offset)  // Définir l'offset
            ->setMaxResults($limit)   // Limiter le nombre d'éléments par page
            ->getQuery()
            ->getResult();
    
        // Calcul du nombre total d'éléments
        $totalItems = count($misesEnSceneRepository->findAll()); // Nombre total d'éléments sans pagination
    
        // Calcul du nombre total de pages
        $totalPages = ceil($totalItems / $limit);
    
        // Passer les données à la vue
        return $this->render('mises_en_scene/index.html.twig', [
            'mises_en_scene' => $mises_en_scenes,
            'currentPage' => $page,
            'totalPages' => $totalPages,
        ]);
    }

    #[Route('/new', name: 'app_mises_en_scene_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
{
    $misesEnScene = new MisesEnScene(); // Le tableau $pictures est initialisé dans l'entité

    $form = $this->createForm(MisesEnSceneType::class, $misesEnScene);
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
                    $misesEnScene->addPicture($newFilename);
                } catch (FileException $e) {
                    // Gestion des erreurs si nécessaire
                    throw new \Exception('Erreur lors du téléchargement d\'un fichier.');
                }
            }
        }

        // Persister et sauvegarder l'entité
        $entityManager->persist($misesEnScene);
        $entityManager->flush();

        return $this->redirectToRoute('app_mises_en_scene_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('mises_en_scene/new.html.twig', [
        'mises_en_scene' => $misesEnScene,
        'form' => $form,
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
