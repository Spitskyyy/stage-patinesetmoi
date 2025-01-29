<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\SecteurPubliqueMonumentHistorique;
use App\Form\SecteurPubliqueMonumentHistoriqueType;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Repository\SecteurPubliqueMonumentHistoriqueRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/secteur_publique_monument_historique')]
final class SecteurPubliqueMonumentHistoriqueController extends AbstractController
{
    #[Route(name: 'app_secteur_publique_monument_historique_index', methods: ['GET'])]
    public function index(Request $request, SecteurPubliqueMonumentHistoriqueRepository $secteurPubliqueMonumentHistoriqueRepository): Response
    {
        // Nombre d'éléments par page
        $limit = 6;
    
        // Page actuelle, récupérée via le paramètre 'page' dans l'URL, par défaut 1
        $page = $request->query->getInt('page', 1);
    
        // Calcul de l'offset (la ligne de départ pour la requête)
        $offset = ($page - 1) * $limit;
    
        // Récupérer les éléments de la page actuelle
        $secteur_publique_monument_historiques = $secteurPubliqueMonumentHistoriqueRepository->createQueryBuilder('a')
            ->orderBy('a.title', 'ASC') // Tri par titre (ordre croissant)
            ->setFirstResult($offset)  // Définir l'offset
            ->setMaxResults($limit)   // Limiter le nombre d'éléments par page
            ->getQuery()
            ->getResult();
    
        // Calcul du nombre total d'éléments
        $totalItems = count($secteurPubliqueMonumentHistoriqueRepository->findAll()); // Nombre total d'éléments sans pagination
    
        // Calcul du nombre total de pages
        $totalPages = ceil($totalItems / $limit);
    
        // Passer les données à la vue
        return $this->render('secteur_publique_monument_historique/index.html.twig', [
            'secteur_publique_monument_historique' => $secteur_publique_monument_historiques,
            'currentPage' => $page,
            'totalPages' => $totalPages,
        ]);
    }

    #[Route('/new', name: 'app_secteur_publique_monument_historique_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $secteurPubliqueMonumentHistorique = new SecteurPubliqueMonumentHistorique();
    
        // Crée le formulaire en y incluant le champ pour les fichiers
        $form = $this->createForm(SecteurPubliqueMonumentHistoriqueType::class, $secteurPubliqueMonumentHistorique);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer les fichiers téléchargés via le formulaire
            $pictureFiles = $form->get('pictures')->getData();  // Assurez-vous que 'pictures' est bien le nom du champ dans le formulaire
    
            if ($pictureFiles) {
                foreach ($pictureFiles as $pictureFile) {
                    // Générer un nom unique pour chaque fichier
                    $originalFilename = pathinfo($pictureFile->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $safeFilename . '-' . uniqid() . '.' . $pictureFile->guessExtension();
    
                    try {
                        // Déplacer le fichier vers le répertoire de stockage configuré
                        $pictureFile->move(
                            $this->getParameter('pictures_directory'), // Vérifiez que ce paramètre est bien configuré dans services.yaml
                            $newFilename
                        );
    
                        // Ajouter le nom du fichier à l'entité (supposons qu'il y ait une méthode addPicture)
                        $secteurPubliqueMonumentHistorique->addPicture($newFilename);
                    } catch (FileException $e) {
                        // Gestion des erreurs d'upload
                        throw new \Exception('Erreur lors du téléchargement d\'un fichier.');
                    }
                }
            }
    
            // Sauvegarder l'entité dans la base de données
            $entityManager->persist($secteurPubliqueMonumentHistorique);
            $entityManager->flush();
    
            // Rediriger vers la page d'index
            return $this->redirectToRoute('app_secteur_publique_monument_historique_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->render('secteur_publique_monument_historique/new.html.twig', [
            'secteur_publique_monument_historique' => $secteurPubliqueMonumentHistorique,
            'form' => $form,
        ]);
    }
    

    #[Route('/{id}', name: 'app_secteur_publique_monument_historique_show', methods: ['GET'])]
    public function show(SecteurPubliqueMonumentHistorique $secteurPubliqueMonumentHistorique): Response
    {
        return $this->render('secteur_publique_monument_historique/show.html.twig', [
            'secteur_publique_monument_historique' => $secteurPubliqueMonumentHistorique,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_secteur_publique_monument_historique_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, SecteurPubliqueMonumentHistorique $secteurPubliqueMonumentHistorique, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SecteurPubliqueMonumentHistoriqueType::class, $secteurPubliqueMonumentHistorique);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_secteur_publique_monument_historique_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('secteur_publique_monument_historique/edit.html.twig', [
            'secteur_publique_monument_historique' => $secteurPubliqueMonumentHistorique,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_secteur_publique_monument_historique_delete', methods: ['POST'])]
    public function delete(Request $request, SecteurPubliqueMonumentHistorique $secteurPubliqueMonumentHistorique, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$secteurPubliqueMonumentHistorique->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($secteurPubliqueMonumentHistorique);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_secteur_publique_monument_historique_index', [], Response::HTTP_SEE_OTHER);
    }
}
