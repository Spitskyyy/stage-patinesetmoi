<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class FauteuilAgrementController extends AbstractController
{
    #[Route('/fauteuil/agrement', name: 'app_fauteuil_agrement')]
    public function index(): Response
    {
        return $this->render('fauteuil_agrement/index.html.twig', [
            'controller_name' => 'FauteuilAgrementController',
        ]);
    }
}
