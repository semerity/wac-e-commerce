<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Panier;
use Symfony\Component\HttpFoundation\Request;

class PanierController extends AbstractController
{
    #[Route('/panier/{id}', name: 'app_panier_get', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager, $id): JsonResponse
    {
        $panierRepository = $entityManager->getRepository(Panier::class);

        $panier = $panierRepository->findProduitsByUserId($id);

        return $this->json($panier);
    }

    #[Route('/panier', name: 'app_panier_post', methods: ['POST'])]
    public function createCart(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $id_product = json_decode($request->getContent())->id_product;
        $id_user = json_decode($request->getContent())->id_user;

        $panier = new Panier();
        $panier->setIdProduct($id_product);
        $panier->setIdUser($id_user);
        $entityManager->persist($panier);
        $entityManager->flush();

        return $this->json($panier->getId());
    }

    #[Route('/panier/', name: 'app_panier_delete', methods: ['DELETE'])]
    public function deleteCart(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        // $id_product = json_decode($request->getContent())->id_product;
        // $id_user = json_decode($request->getContent())->id_user;

        $entityManager->getRepository(Panier::class)->deleteOneProduct(json_decode($request->getContent())->id);

        // $entityManager->remove($panier[0]);
        // $entityManager->flush();

        return $this->json('done');

        
    }#[Route('/panier/{id}', name: 'app_panier_user_delete', methods: ['DELETE'])]
    public function deleteCartByUser($id, EntityManagerInterface $entityManager): JsonResponse
    {

        $entityManager->getRepository(Panier::class)->deleteUser($id);
        return $this->json('done');

        
    }
}
