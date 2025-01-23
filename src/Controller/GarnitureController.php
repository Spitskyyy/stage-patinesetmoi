<?php

namespace App\Controller;

use App\Entity\Garniture;
use App\Form\GarnitureType;
use App\Repository\GarnitureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/garniture')]
final class GarnitureController extends AbstractController
{
    #[Route(name: 'app_garniture_index', methods: ['GET'])]
    public function index(GarnitureRepository $garnitureRepository): Response
    {
        return $this->render('garniture/index.html.twig', [
            'garnitures' => $garnitureRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_garniture_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $garniture = new Garniture();
        $form = $this->createForm(GarnitureType::class, $garniture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
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
