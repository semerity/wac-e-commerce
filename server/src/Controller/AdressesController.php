<?php

namespace App\Controller;

use App\Entity\Adresses;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

class AdressesController extends AbstractController
{
    #[Route('/adresse/{id_user}', name: 'app_adresse_get', methods:'GET')]
    public function getAdresse(EntityManagerInterface $entityManager, $id_user): JsonResponse
    {   
        $adresse = $entityManager->getRepository(Adresses::class)->findByExampleField($id_user);

        if(count($adresse) == 0){
            return $this->json("Liste d'adresse vide", 500);
        }

        return $this->json($adresse);
    }

    #[Route('/adresse', name: 'app_adresse_cte', methods:'POST')]
    public function createAdresse(EntityManagerInterface $entityManager, Request $request)
    {
        $id_user = json_decode($request->getContent())->id_user;
        $pays = json_decode($request->getContent())->pays;
        $ville = json_decode($request->getContent())->ville;
        $code_postal = json_decode($request->getContent())->code_postal;
        $nom_de_rue = json_decode($request->getContent())->nom_de_rue;

        $adresse = New Adresses();
        $adresse->setIdUser($id_user)
            ->setPays($pays)
            ->setVille($ville)
            ->setCodePostal($code_postal)
            ->setNomDeRue($nom_de_rue)
        ;

        $entityManager->persist($adresse);
        $entityManager->flush();

        return $this->json(["Adresse ajouté", $adresse]);
    }   

    #[Route('/adresse/{id}', name: 'app_adresse_delete', methods:'DELETE')]
    public function deleteAdresse(EntityManagerInterface $entityManager, $id)
    {
        $adresse = $entityManager->getRepository(Adresses::class)->findOneBySomeField($id);

        $entityManager->remove($adresse);
        $entityManager->flush();

        return $this->json($adresse);
    }
}
