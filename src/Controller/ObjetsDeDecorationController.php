<?php

namespace App\Controller;

use App\Entity\ObjetsDeDecoration;
use App\Form\ObjetsDeDecorationType;
use App\Repository\ObjetsDeDecorationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/objets/de/decoration')]
final class ObjetsDeDecorationController extends AbstractController
{
    #[Route(name: 'app_objets_de_decoration_index', methods: ['GET'])]
    public function index(ObjetsDeDecorationRepository $objetsDeDecorationRepository): Response
    {
        return $this->render('objets_de_decoration/index.html.twig', [
            'objets_de_decorations' => $objetsDeDecorationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_objets_de_decoration_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $objetsDeDecoration = new ObjetsDeDecoration();
        $form = $this->createForm(ObjetsDeDecorationType::class, $objetsDeDecoration);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($objetsDeDecoration);
            $entityManager->flush();

            return $this->redirectToRoute('app_objets_de_decoration_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('objets_de_decoration/new.html.twig', [
            'objets_de_decoration' => $objetsDeDecoration,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_objets_de_decoration_show', methods: ['GET'])]
    public function show(ObjetsDeDecoration $objetsDeDecoration): Response
    {
        return $this->render('objets_de_decoration/show.html.twig', [
            'objets_de_decoration' => $objetsDeDecoration,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_objets_de_decoration_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ObjetsDeDecoration $objetsDeDecoration, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ObjetsDeDecorationType::class, $objetsDeDecoration);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_objets_de_decoration_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('objets_de_decoration/edit.html.twig', [
            'objets_de_decoration' => $objetsDeDecoration,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_objets_de_decoration_delete', methods: ['POST'])]
    public function delete(Request $request, ObjetsDeDecoration $objetsDeDecoration, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$objetsDeDecoration->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($objetsDeDecoration);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_objets_de_decoration_index', [], Response::HTTP_SEE_OTHER);
    }
}
