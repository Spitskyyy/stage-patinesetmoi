<?php

namespace App\Controller;

use App\Entity\AvantApres;
use App\Form\AvantApresType;
use App\Repository\AvantApresRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/avant_apres')]
final class AvantApresController extends AbstractController
{
    #[Route(name: 'app_avant_apres_index', methods: ['GET'])]
    
    public function index(Request $request, AvantApresRepository $avantApresRepository): Response
    {
        // Nombre d'éléments par page
        $limit = 6;
    
        // Page actuelle, récupérée via le paramètre 'page' dans l'URL, par défaut 1
        $page = $request->query->getInt('page', 1);
    
        // Calcul de l'offset (la ligne de départ pour la requête)
        $offset = ($page - 1) * $limit;
    
        // Récupérer les éléments de la page actuelle
        $avantApress = $avantApresRepository->createQueryBuilder('a')
            ->orderBy('a.title', 'ASC') // Tri par titre (ordre croissant)
            ->setFirstResult($offset)  // Définir l'offset
            ->setMaxResults($limit)   // Limiter le nombre d'éléments par page
            ->getQuery()
            ->getResult();
    
        // Calcul du nombre total d'éléments
        $totalItems = count($avantApresRepository->findAll()); // Nombre total d'éléments sans pagination
    
        // Calcul du nombre total de pages
        $totalPages = ceil($totalItems / $limit);
    
        // Passer les données à la vue
        return $this->render('avant_apres/index.html.twig', [
            'avant_apres' => $avantApress,
            'currentPage' => $page,
            'totalPages' => $totalPages,
        ]);
    }


    #[Route('/new', name: 'app_avant_apres_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $avantApre = new AvantApres(); // Le tableau $pictures est initialisé dans l'entité
    
        $form = $this->createForm(AvantApresType::class, $avantApre);
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
                        $avantApre->addPicture($newFilename);
                    } catch (FileException $e) {
                        // Gestion des erreurs si nécessaire
                        throw new \Exception('Erreur lors du téléchargement d\'un fichier.');
                    }
                }
            }
    
            // Persister et sauvegarder l'entité
            $entityManager->persist($avantApre);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_avant_apres_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->render('avant_apres/new.html.twig', [
            'avant_apre' => $avantApre,
            'form' => $form,
        ]);
    }
    


    #[Route('/{id}', name: 'app_avant_apres_show', methods: ['GET'])]
    public function show(AvantApres $avantApre): Response
    {
        return $this->render('avant_apres/show.html.twig', [
            'avant_apre' => $avantApre,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_avant_apres_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, AvantApres $avantApre, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AvantApresType::class, $avantApre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_avant_apres_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('avant_apres/edit.html.twig', [
            'avant_apre' => $avantApre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_avant_apres_delete', methods: ['POST'])]
    public function delete(Request $request, AvantApres $avantApre, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$avantApre->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($avantApre);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_avant_apres_index', [], Response::HTTP_SEE_OTHER);
    }
}
