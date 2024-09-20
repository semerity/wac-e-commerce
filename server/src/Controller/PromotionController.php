<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Promotion;
use App\Entity\User;

class PromotionController extends AbstractController
{
    #[Route('/promo', name: 'app_promo_get', methods:'GET')]
    public function getAdresse(EntityManagerInterface $entityManager, Request $request): JsonResponse
    {   
        $promo = $entityManager->getRepository(Promotion::class)->findAll();

        return $this->json($promo);
    }

    #[Route('/promo', name: 'app_promo_create', methods:'POST')]
    public function createAdresse(EntityManagerInterface $entityManager, Request $request)
    {
        $id_product = json_decode($request->getContent())->id_product;
        $promo = json_decode($request->getContent())->promo;
        $is_discounted = 0;
        $promotion = New Promotion();
        $promotion->setIdProduct($id_product)
            ->setPromotion($promo)
            ->setDiscounted($is_discounted)
        ;

        $entityManager->persist($promotion);
        $entityManager->flush();

        return $this->json(["Adresse ajouté"]);
    }   

    #[Route('/promo', name: 'app_promo_create', methods:'PATCH')]
    public function UpdatePromotion(EntityManagerInterface $entityManager, Request $request)
    {
        $request = json_decode($request->getContent());
        $product = $entityManager->getRepository(Promotion::class)->find($request->id_product);
        $role = $entityManager->getRepository(User::class)->find($request->id_user)->getRoles();

        if (in_array('ROLE_ADMIN', $role)){
            $product->setDiscounted($request->is_discounted);
            $product->setPromotion($request->promo);
        }
    }

}
