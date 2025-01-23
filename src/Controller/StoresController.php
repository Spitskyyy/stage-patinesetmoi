<?php

namespace App\Controller;

use App\Entity\Stores;
use App\Form\StoresType;
use App\Repository\StoresRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/stores')]
final class StoresController extends AbstractController
{
    #[Route(name: 'app_stores_index', methods: ['GET'])]
    public function index(StoresRepository $storesRepository): Response
    {
        return $this->render('stores/index.html.twig', [
            'stores' => $storesRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_stores_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $store = new Stores();
        $form = $this->createForm(StoresType::class, $store);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($store);
            $entityManager->flush();

            return $this->redirectToRoute('app_stores_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('stores/new.html.twig', [
            'store' => $store,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_stores_show', methods: ['GET'])]
    public function show(Stores $store): Response
    {
        return $this->render('stores/show.html.twig', [
            'store' => $store,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_stores_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Stores $store, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(StoresType::class, $store);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_stores_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('stores/edit.html.twig', [
            'store' => $store,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_stores_delete', methods: ['POST'])]
    public function delete(Request $request, Stores $store, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$store->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($store);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_stores_index', [], Response::HTTP_SEE_OTHER);
    }
}
