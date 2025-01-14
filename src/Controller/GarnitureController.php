<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class GarnitureController extends AbstractController
{
    #[Route('/garniture', name: 'app_garniture')]
    public function index(): Response
    {
        return $this->render('garniture/index.html.twig', [
            'controller_name' => 'GarnitureController',
        ]);
    }
}
