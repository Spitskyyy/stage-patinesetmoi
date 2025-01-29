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
    public function index(Request $request, ObjetsDeDecorationRepository $objetsDeDecorationRepository): Response
    {
        // Nombre d'éléments par page
        $limit = 6;
    
        // Page actuelle, récupérée via le paramètre 'page' dans l'URL, par défaut 1
        $page = $request->query->getInt('page', 1);
    
        // Calcul de l'offset (la ligne de départ pour la requête)
        $offset = ($page - 1) * $limit;
    
        // Récupérer les éléments de la page actuelle
        $objets_de_decorations = $objetsDeDecorationRepository->createQueryBuilder('a')
            ->orderBy('a.title', 'ASC') // Tri par titre (ordre croissant)
            ->setFirstResult($offset)  // Définir l'offset
            ->setMaxResults($limit)   // Limiter le nombre d'éléments par page
            ->getQuery()
            ->getResult();
    
        // Calcul du nombre total d'éléments
        $totalItems = count($objetsDeDecorationRepository->findAll()); // Nombre total d'éléments sans pagination
    
        // Calcul du nombre total de pages
        $totalPages = ceil($totalItems / $limit);
    
        // Passer les données à la vue
        return $this->render('objets_de_decoration/index.html.twig', [
            'objets_de_decoration' => $objets_de_decorations,
            'currentPage' => $page,
            'totalPages' => $totalPages,
        ]);
    }



    #[Route('/new', name: 'app_objets_de_decoration_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $objetsDeDecoration = new ObjetsDeDecoration(); // Le tableau $pictures est initialisé dans l'entité
    
        $form = $this->createForm(ObjetsDeDecorationType::class, $objetsDeDecoration);
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
                        $objetsDeDecoration->addPicture($newFilename);
                    } catch (FileException $e) {
                        // Gestion des erreurs si nécessaire
                        throw new \Exception('Erreur lors du téléchargement d\'un fichier.');
                    }
                }
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
