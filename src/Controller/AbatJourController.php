<?php

namespace App\Controller;

use App\Entity\AbatJour;
use App\Form\AbatJourType;
use App\Repository\AbatJourRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/abat_jour')]
final class AbatJourController extends AbstractController
{
    #[Route(name: 'app_abat_jour_index', methods: ['GET'])]
    public function index(Request $request, AbatJourRepository $abatJourRepository): Response
    {
        // Nombre d'éléments par page
        $limit = 6;
    
        // Page actuelle, avec une valeur minimale de 1
        $page = max(1, $request->query->getInt('page', 1));
    
        // Calcul du nombre total d'éléments
        $totalItems = $abatJourRepository->count([]); // Optimisation pour éviter un chargement massif en mémoire
    
        // Calcul du nombre total de pages, avec un minimum de 1
        $totalPages = max(1, ceil($totalItems / $limit));
    
        // Vérifie que la page courante ne dépasse pas le total des pages
        $page = min($page, $totalPages);
    
        // Calcul de l'offset (départ des résultats pour la requête)
        $offset = ($page - 1) * $limit;
    
        // Récupérer les éléments de la page actuelle
        $abatJours = $abatJourRepository->createQueryBuilder('a')
            ->orderBy('a.title', 'ASC') // Tri par titre (ordre croissant)
            ->setFirstResult($offset)  // Définir l'offset
            ->setMaxResults($limit)   // Limiter le nombre d'éléments par page
            ->getQuery()
            ->getResult();
    
        // Passer les données à la vue
        return $this->render('abat_jour/index.html.twig', [
            'abat_jours' => $abatJours,
            'currentPage' => $page,
            'totalPages' => $totalPages,
        ]);
    }
    
    #[Route('/new', name: 'app_abat_jour_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
{
    $abatJour = new AbatJour(); // Pas besoin d'initialiser $pictures ici, c'est fait dans l'entité

    $form = $this->createForm(AbatJourType::class, $abatJour);
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
                    $abatJour->addPicture($newFilename);
                } catch (FileException $e) {
                    // Gestion des erreurs si nécessaire
                    throw new \Exception('Erreur lors du téléchargement d\'un fichier.');
                }
            }
        }

        // Persister et sauvegarder l'entité
        $entityManager->persist($abatJour);
        $entityManager->flush();

        return $this->redirectToRoute('app_abat_jour_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('abat_jour/new.html.twig', [
        'abat_jour' => $abatJour,
        'form' => $form,
    ]);
}



#[Route('/{id}', name: 'app_abat_jour_show', methods: ['GET'])]
public function show(AbatJour $abatJour): Response
{
    return $this->render('abat_jour/show.html.twig', [
        'abat_jour' => $abatJour,
        'pictures' => $abatJour->getPictures(), // Récupération des images
    ]);
}


    #[Route('/{id}/edit', name: 'app_abat_jour_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, AbatJour $abatJour, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AbatJourType::class, $abatJour);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_abat_jour_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('abat_jour/edit.html.twig', [
            'abat_jour' => $abatJour,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_abat_jour_delete', methods: ['POST'])]
    public function delete(Request $request, AbatJour $abatJour, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$abatJour->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($abatJour);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_abat_jour_index', [], Response::HTTP_SEE_OTHER);
    }
}
