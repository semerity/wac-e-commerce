<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Theme;
use App\Entity\Produit;

class AcceuilController extends AbstractController
{
    #[Route('/', name: 'app_acceuil')]
    public function index(EntityManagerInterface $entityManager): JsonResponse
    {
        // dd($entityManager->getRepository(Produit::class)->findAll());
        $productRepository = $entityManager->getRepository(Produit::class);
        // dd($productRepository->getAllProducts());
        // return $this->json($entityManager->getRepository(Produit::class)->findAll()->toArray());
        return $this->json($productRepository->getAllProducts());
    }
}
