<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\HttpFoundation\Request;
use App\Entity\Avis;
use App\Entity\User;

class AvisController extends AbstractController
{
    #[Route('/avis', name: 'app_avis_get', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager, Request $request): JsonResponse
    {
        $id_product = intval($request->query->get('id_product'));
        $avisRepository = $entityManager->getRepository(Avis::class);
        // $request = json_decode($request->getContent());

        // return $this->json($avisRepository->getOneProduct($request->id_product));
        return $this->json($avisRepository->getOneProduct($id_product));
    }

    #[Route('/avis', name: 'app_avis_add', methods: ['POST'])]
    public function createAvis(EntityManagerInterface $entityManager, Request $request): JsonResponse
    {
        $request = json_decode($request->getContent());
        $avis = new Avis();
        $avis->setNote($request->note);
        $avis->setIdProduct($request->id_product);
        $avis->setContent($request->content);
        $avis->setIdUser($request->id_user);
        $entityManager->persist($avis);
        $entityManager->flush();

        return $this->json('Note ajoutée.');

    }

    //PAS ENCORE FAIS
    #[Route('/avis', name: 'app_avis_delete', methods: ['DELETE'])]
    public function deleteAvis(EntityManagerInterface $entityManager, Request $request): JsonResponse
    {
        $request = json_decode($request->getContent());

        $avis = $entityManager->getRepository(Avis::class)->find($request->id_avis);
        $role = $entityManager->getRepository(User::class)->find($request->id_user)->getRoles();

        if (in_array('ROLE_ADMIN', $role)) {
            $entityManager->remove($avis);
            $entityManager->flush();

            return $this->json('Thème supprimé.');
        }
    }

    //PAS ENCORE FAIS
    #[Route('/avis', name: 'app_avis_update', methods: ['PATCH'])]
    public function updateAvis(EntityManagerInterface $entityManager, Request $request): JsonResponse
    {
        $request = json_decode($request->getContent());

        $avis = $entityManager->getRepository(Avis::class)->find($request->id_avis);
        $role = $entityManager->getRepository(User::class)->find($request->id_user)->getRoles();

        if (in_array('ROLE_ADMIN', $role)) {
            $avis->setAvis($request->name);
            $entityManager->flush();

            return $this->json([
                'Thème modifié !'
            ]);
        }

        return $this->json('Thème édité.');
    }


    // #[Route('/avis', name: 'app_avis_add'), methods:['POST']]
    // public function addAvis(Request $request, EntityManagerInterface $entityManager): JsonResponse
    // {
    //     $avis = new Avis();
    //     $avis->setAvis();
    //     $avis->setColor(NULL);

    //     return $this->json();
    // }
}
