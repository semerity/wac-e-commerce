<?php

namespace App\Controller;

use App\Entity\Pays;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PaysController extends AbstractController
{
    #[Route('/pays', name: 'app_pays', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $paysRepo = $entityManager->getRepository(Pays::class);
        $pays = $paysRepo->findAll();

        return $this->json($pays, 200);
    }

    #[Route('/admin/pays/{id}', name: 'app_pays_get', methods: ['GET'])]
    public function user(EntityManagerInterface $entityManager, $id): Response
    {
        $pays = $entityManager->getRepository(Pays::class)->findOneByid($id);

        return $this->json([
            'method' => 'get',
            'id' => $id,
            'pays' => $pays
        ], 200);
    }

    #[Route('/admin/pays', name: 'app_pays_add', methods: ['POST'])]
    public function addUser(Request $request, EntityManagerInterface $entityManager): Response
    {
        $pays = new Pays();
        $data = json_decode($request->getContent());
        if (isset($data->nom) && isset($data->tarif)) {
                $pays->setNom($data->nom);
                $pays->setTarif($data->tarif);

                $entityManager->persist($pays);
                $entityManager->flush();

                return $this->json(['success' => 'successfully registered'], 200);
        } else {
            return $this->json([
                'error' => 'Incorrect request : no nom',
                'data' => $data
            ], 400);
        }
    }

    #[Route('/admin/pays/{id}', name: 'app_pays_patch', methods: ['PATCH'])]
    public function changeUser(Request $request, EntityManagerInterface $entityManager, $id): Response
    {
        $pays = $entityManager->getRepository(Pays::class)->find($id);
        $data = json_decode($request->getContent());
        if (isset($data->nom) && isset($data->tarif)) {
                // encode the plain password
                $pays->setNom($data->nom);
                $pays->setTarif($data->tarif);

                $entityManager->persist($pays);
                $entityManager->flush();

                return $this->json(['success' => 'successfully registered'], 200);
        } else {
            return $this->json([
                'error' => 'Incorrect request : no nom',
                'data' => $data
            ], 400);
        }
    }

    #[Route('/admin/pays/{id}', name: 'app_pays_del', methods: ['DELETE'])]
    public function deleteUser(EntityManagerInterface $entityManager, $id): Response
    {
        $user = $entityManager->getRepository(Pays::class)->find($id);

        $entityManager->remove($user);
        $entityManager->flush();

        return $this->json(['success' => 'successfully deleted'], 200);
    }
}
