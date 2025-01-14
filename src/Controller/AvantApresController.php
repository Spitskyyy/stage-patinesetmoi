<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AvantApresController extends AbstractController
{
    #[Route('/avant/apres', name: 'app_avant_apres')]
    public function index(): Response
    {
        return $this->render('avant_apres/index.html.twig', [
            'controller_name' => 'AvantApresController',
        ]);
    }
}
