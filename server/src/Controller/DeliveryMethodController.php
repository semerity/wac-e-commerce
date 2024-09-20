<?php

namespace App\Controller;

use App\Entity\DeliveryMethod;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DeliveryMethodController extends AbstractController
{
    #[Route('/delMethod', name: 'app_delMethod', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $paysRepo = $entityManager->getRepository(DeliveryMethod::class);
        $pays = $paysRepo->findAll();

        return $this->json($pays, 200);
    }

    // #[Route('/admin/pays/{id}', name: 'app_delMethod_get', methods: ['GET'])]
    // public function user(EntityManagerInterface $entityManager, $id): Response
    // {
    //     $pays = $entityManager->getRepository(DeliveryMethod::class)->findOneByid($id);

    //     return $this->json([
    //         'method' => 'get',
    //         'id' => $id,
    //         'pays' => $pays
    //     ], 200);
    // }

    #[Route('/delMethod', name: 'app_delMethod_add', methods: ['POST'])]
    public function addUser(Request $request, EntityManagerInterface $entityManager): Response
    {
        $pays = new DeliveryMethod();
        $data = json_decode($request->getContent());
        if (isset($data->name) && isset($data->mult)) {
                $pays->setName($data->name);
                $pays->setMult($data->mult);

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

    #[Route('/delMethod/{id}', name: 'app_delMethod_patch', methods: ['PATCH'])]
    public function changeUser(Request $request, EntityManagerInterface $entityManager, $id): Response
    {
        $pays = $entityManager->getRepository(DeliveryMethod::class)->find($id);
        $data = json_decode($request->getContent());
        if (isset($data->name) && isset($data->mult)) {
                // encode the plain password
                $pays->setName($data->name);
                $pays->setMult($data->mult);

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

    #[Route('/delMethod/{id}', name: 'app_delMethod_del', methods: ['DELETE'])]
    public function deleteUser(EntityManagerInterface $entityManager, $id): Response
    {
        $user = $entityManager->getRepository(DeliveryMethod::class)->find($id);

        $entityManager->remove($user);
        $entityManager->flush();

        return $this->json(['success' => 'successfully deleted'], 200);
    }
}
