<?php

namespace App\Controller;

use App\Entity\LivreDor;
use App\Form\LivreDorType;
use App\Repository\LivreDorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/livre/dor')]
final class LivreDorController extends AbstractController
{
    #[Route(name: 'app_livre_dor_index', methods: ['GET'])]
    public function index(LivreDorRepository $livreDorRepository): Response
    {
        return $this->render('livre_dor/index.html.twig', [
            'livre_dors' => $livreDorRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_livre_dor_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $livreDor = new LivreDor();
        $form = $this->createForm(LivreDorType::class, $livreDor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($livreDor);
            $entityManager->flush();

            return $this->redirectToRoute('app_livre_dor_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('livre_dor/new.html.twig', [
            'livre_dor' => $livreDor,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_livre_dor_show', methods: ['GET'])]
    public function show(LivreDor $livreDor): Response
    {
        return $this->render('livre_dor/show.html.twig', [
            'livre_dor' => $livreDor,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_livre_dor_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, LivreDor $livreDor, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LivreDorType::class, $livreDor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_livre_dor_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('livre_dor/edit.html.twig', [
            'livre_dor' => $livreDor,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_livre_dor_delete', methods: ['POST'])]
    public function delete(Request $request, LivreDor $livreDor, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$livreDor->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($livreDor);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_livre_dor_index', [], Response::HTTP_SEE_OTHER);
    }
}
