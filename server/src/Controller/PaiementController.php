<?php

namespace App\Controller;

use App\Entity\Paiement;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;


class PaiementController extends AbstractController
{
    #[Route('/paiement', name: 'app_paiement_create', methods: "POST")]
    public function createPaiement(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $id_user = json_decode($request->getContent())->id_user;
        $name = json_decode($request->getContent())->name;
        $code = json_decode($request->getContent())->code;
        $date = json_decode($request->getContent())->date;
        // $crypto = json_decode($request->getContent())->crypto;
        $crypto = 000;
             
        $paiement = New Paiement();
        $paiement->setIdUser($id_user)
            ->setName($name)
            ->setCode($code)
            ->setDate($date)
            ->setCryptogramme($crypto);
        $entityManager->persist($paiement);
        $entityManager->flush();
        
        return $this->json("Moyen de paiement ajouté.");
    }

    #[Route('/paiement/{id}', name: 'app_paiement_delete', methods: "DELETE")]
    public function UpdatePaiement(EntityManagerInterface $entityManager, $id): JsonResponse
    {
        $paiement = $entityManager->getRepository(Paiement::class)->findOneBySomeField($id);

        $entityManager->remove($paiement);
        $entityManager->flush();

        return $this->json([$paiement]);
    }

    #[Route('/paiement/{id}', name: 'app_paiement_get', methods: "GET")]
    public function getPaiement(EntityManagerInterface $entityManager, $id): JsonResponse
    {
        $paiement = $entityManager->getRepository(Paiement::class)->findByExampleField($id);

        return $this->json($paiement);
    }


}
