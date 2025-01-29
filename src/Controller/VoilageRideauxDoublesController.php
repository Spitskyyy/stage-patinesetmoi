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
    public function index(Request $request, VoilageRideauxDoublesRepository $voilageRideauxDoublesRepository): Response
    {
        // Nombre d'éléments par page
        $limit = 6;
    
        // Page actuelle, récupérée via le paramètre 'page' dans l'URL, par défaut 1
        $page = $request->query->getInt('page', 1);
    
        // Calcul de l'offset (la ligne de départ pour la requête)
        $offset = ($page - 1) * $limit;
    
        // Récupérer les éléments de la page actuelle
        $voilage_rideaux_doubless = $voilageRideauxDoublesRepository->createQueryBuilder('a')
            ->orderBy('a.title', 'ASC') // Tri par titre (ordre croissant)
            ->setFirstResult($offset)  // Définir l'offset
            ->setMaxResults($limit)   // Limiter le nombre d'éléments par page
            ->getQuery()
            ->getResult();
    
        // Calcul du nombre total d'éléments
        $totalItems = count($voilageRideauxDoublesRepository->findAll()); // Nombre total d'éléments sans pagination
    
        // Calcul du nombre total de pages
        $totalPages = ceil($totalItems / $limit);
    
        // Passer les données à la vue
        return $this->render('voilage_rideaux_doubles/index.html.twig', [
            'voilage_rideaux_doubles' => $voilage_rideaux_doubless,
            'currentPage' => $page,
            'totalPages' => $totalPages,
        ]);
    }



    #[Route('/new', name: 'app_voilage_rideaux_doubles_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $voilageRideauxDouble = new VoilageRideauxDoubles(); // Le tableau $pictures est initialisé dans l'entité
    
        $form = $this->createForm(VoilageRideauxDoublesType::class, $voilageRideauxDouble);
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
                        $voilageRideauxDouble->addPicture($newFilename);
                    } catch (FileException $e) {
                        // Gestion des erreurs si nécessaire
                        throw new \Exception('Erreur lors du téléchargement d\'un fichier.');
                    }
                }
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
