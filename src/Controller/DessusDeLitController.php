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

#[Route('/dessus_de_lit')]
final class DessusDeLitController extends AbstractController
{
    #[Route(name: 'app_dessus_de_lit_index', methods: ['GET'])]
    public function index(Request $request, DessusDeLitRepository $dessus_de_litRepository): Response
    {
        // Nombre d'éléments par page
        $limit = 6;
    
        // Page actuelle, récupérée via le paramètre 'page' dans l'URL, par défaut 1
        $page = $request->query->getInt('page', 1);
    
        // Calcul de l'offset (la ligne de départ pour la requête)
        $offset = ($page - 1) * $limit;
    
        // Récupérer les éléments de la page actuelle
        $dessus_de_lits = $dessus_de_litRepository->createQueryBuilder('a')
            ->orderBy('a.title', 'ASC') // Tri par titre (ordre croissant)
            ->setFirstResult($offset)  // Définir l'offset
            ->setMaxResults($limit)   // Limiter le nombre d'éléments par page
            ->getQuery()
            ->getResult();
    
        // Calcul du nombre total d'éléments
        $totalItems = count($dessus_de_litRepository->findAll()); // Nombre total d'éléments sans pagination
    
        // Calcul du nombre total de pages
        $totalPages = ceil($totalItems / $limit);
    
        // Passer les données à la vue
        return $this->render('dessus_de_lit/index.html.twig', [
            'dessus_de_lit' => $dessus_de_lits,
            'currentPage' => $page,
            'totalPages' => $totalPages,
        ]);
    }

    #[Route('/new', name: 'app_dessus_de_lit_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $dessusDeLit = new DessusDeLit(); // Le tableau $pictures est initialisé dans l'entité
    
        $form = $this->createForm(DessusDeLitType::class, $dessusDeLit);
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
                        $dessusDeLit->addPicture($newFilename);
                    } catch (FileException $e) {
                        // Gestion des erreurs si nécessaire
                        throw new \Exception('Erreur lors du téléchargement d\'un fichier.');
                    }
                }
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
