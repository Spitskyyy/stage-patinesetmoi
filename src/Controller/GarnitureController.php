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
    public function index(Request $request, GarnitureRepository $garnitureRepository): Response
    {
        // Nombre d'éléments par page
        $limit = 6;
    
        // Page actuelle, récupérée via le paramètre 'page' dans l'URL, par défaut 1
        $page = $request->query->getInt('page', 1);
    
        // Calcul de l'offset (la ligne de départ pour la requête)
        $offset = ($page - 1) * $limit;
    
        // Récupérer les éléments de la page actuelle
        $garnitures = $garnitureRepository->createQueryBuilder('a')
            ->orderBy('a.title', 'ASC') // Tri par titre (ordre croissant)
            ->setFirstResult($offset)  // Définir l'offset
            ->setMaxResults($limit)   // Limiter le nombre d'éléments par page
            ->getQuery()
            ->getResult();
    
        // Calcul du nombre total d'éléments
        $totalItems = count($garnitureRepository->findAll()); // Nombre total d'éléments sans pagination
    
        // Calcul du nombre total de pages
        $totalPages = ceil($totalItems / $limit);
    
        // Passer les données à la vue
        return $this->render('garniture/index.html.twig', [
            'garniture' => $garnitures,
            'currentPage' => $page,
            'totalPages' => $totalPages,
        ]);
    }

    #[Route('/new', name: 'app_garniture_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $garniture = new Garniture(); // Le tableau $pictures est initialisé dans l'entité
    
        $form = $this->createForm(GarnitureType::class, $garniture);
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
                        $garniture->addPicture($newFilename);
                    } catch (FileException $e) {
                        // Gestion des erreurs si nécessaire
                        throw new \Exception('Erreur lors du téléchargement d\'un fichier.');
                    }
                }
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
