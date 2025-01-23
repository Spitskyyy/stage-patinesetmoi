<?php

namespace App\Controller;

use App\Entity\AvantApres;
use App\Form\AvantApresType;
use App\Repository\AvantApresRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/avant/apres')]
final class AvantApresController extends AbstractController
{
    #[Route(name: 'app_avant_apres_index', methods: ['GET'])]
    public function index(AvantApresRepository $avantApresRepository): Response
    {
        return $this->render('avant_apres/index.html.twig', [
            'avant_apres' => $avantApresRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_avant_apres_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $avantApre = new AvantApres();
        $form = $this->createForm(AvantApresType::class, $avantApre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
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
