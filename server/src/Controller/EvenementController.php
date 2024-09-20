<?php

namespace App\Controller;

use App\Entity\Evenement;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class EvenementController extends AbstractController
{
    #[Route('/evenement', name: 'app_evenement', methods:'GET')]
    public function index(EntityManagerInterface $entityManager): JsonResponse
    {
        return $this->json($entityManager->getRepository(Evenement::class)->findAll());
    }

    #[Route('/evenement', name: 'app_evenement_post', methods:'POST')]
    public function createEvenement(EntityManagerInterface $entityManager, Request $request): JsonResponse
    {
        $evenement = New Evenement();
        $evenement->setDateDebut(json_decode($request->getContent())->date_debut)
            ->setDateFin(json_decode($request->getContent())->date_fin)
            ->setNom(json_decode($request->getContent())->nom);
        $entityManager->persist($evenement);
        $entityManager->flush();

        return $this->json(["Evenement bien ajouté", $evenement]);
    }

    #[Route('/evenement/{id}', name: 'app_evenement_delete', methods:'DELETE')]
    public function deleteEvenement(EntityManagerInterface $entityManager, $id): JsonResponse
    {
        $evenement = $entityManager->getRepository(Evenement::class)->findOneBySomeField($id);

        $entityManager->remove($evenement);
        $entityManager->flush();

        return $this->json($evenement);
    }
}
