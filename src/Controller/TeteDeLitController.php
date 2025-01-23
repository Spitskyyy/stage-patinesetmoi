<?php

namespace App\Controller;

use App\Entity\TeteDeLit;
use App\Form\TeteDeLitType;
use App\Repository\TeteDeLitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/tete/de/lit')]
final class TeteDeLitController extends AbstractController
{
    #[Route(name: 'app_tete_de_lit_index', methods: ['GET'])]
    public function index(TeteDeLitRepository $teteDeLitRepository): Response
    {
        return $this->render('tete_de_lit/index.html.twig', [
            'tete_de_lits' => $teteDeLitRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_tete_de_lit_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $teteDeLit = new TeteDeLit();
        $form = $this->createForm(TeteDeLitType::class, $teteDeLit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($teteDeLit);
            $entityManager->flush();

            return $this->redirectToRoute('app_tete_de_lit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('tete_de_lit/new.html.twig', [
            'tete_de_lit' => $teteDeLit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_tete_de_lit_show', methods: ['GET'])]
    public function show(TeteDeLit $teteDeLit): Response
    {
        return $this->render('tete_de_lit/show.html.twig', [
            'tete_de_lit' => $teteDeLit,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_tete_de_lit_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, TeteDeLit $teteDeLit, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TeteDeLitType::class, $teteDeLit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_tete_de_lit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('tete_de_lit/edit.html.twig', [
            'tete_de_lit' => $teteDeLit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_tete_de_lit_delete', methods: ['POST'])]
    public function delete(Request $request, TeteDeLit $teteDeLit, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$teteDeLit->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($teteDeLit);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_tete_de_lit_index', [], Response::HTTP_SEE_OTHER);
    }
}
