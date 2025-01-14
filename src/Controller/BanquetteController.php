<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BanquetteController extends AbstractController
{
    #[Route('/banquette', name: 'app_banquette')]
    public function index(): Response
    {
        return $this->render('banquette/index.html.twig', [
            'controller_name' => 'BanquetteController',
        ]);
    }
}
