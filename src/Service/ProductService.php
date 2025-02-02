<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

class ProductService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getLatestProducts(int $limit = 10): array
    {
        $repositories = [
            'App\Entity\AbatJour',
            'App\Entity\Banquette',
            'App\Entity\DessusDeLit',
            'App\Entity\FauteuilDagrément',
            'App\Entity\Garniture',
            'App\Entity\LivreDor',
            'App\Entity\MisesEnScene',
            'App\Entity\ObjetsDeDecoration',
            'App\Entity\PapierPeint',
            'App\Entity\SecteurPubliqueMonumentHistorique',
            'App\Entity\Stores',
            'App\Entity\TeteDeLit',
            'App\Entity\Tringlerie',
            'App\Entity\VoilageRideauxDoubles',
        ];

        $products = [];

        foreach ($repositories as $entity) {
            $repo = $this->entityManager->getRepository($entity);
            $latest = $repo->findBy([], ['createdAt' => 'DESC'], $limit);
            $products = array_merge($products, $latest);
        }

        // Trier tous les produits par date
        usort($products, function ($a, $b) {
            return $b->getCreatedAt() <=> $a->getCreatedAt();
        });

        return array_slice($products, 0, $limit);
    }
} 