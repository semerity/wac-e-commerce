<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Produit;
use App\Entity\User;
use PhpParser\Builder\Method;
use Symfony\Component\HttpFoundation\Request;

class ProductController extends AbstractController
{
    #[Route('/produit/{id}', name: 'app_product_id')]

    public function index(EntityManagerInterface $entityManager, $id): JsonResponse
    {
        $productRepository = $entityManager->getRepository(Produit::class);

        $product = $productRepository->find($id);
        // if($product){
            $product->setPopularite($productRepository->getOneProduct($id)[0]["popularite"] + 1);
            $entityManager->flush();
        // }

        return $this->json($productRepository->getOneProduct($id)[0]);
    }

    #[Route('/produit', name: 'app_product_add', methods: ["POST"])]
    public function add(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $user = json_decode($request->getContent())->id_user;
        $role = $entityManager->getRepository(User::class)->find($user)->getRoles();


        if (in_array('ROLE_ADMIN', $role)) {
            $product = new Produit();
            $product->setIdTheme(json_decode($request->getContent())->id_theme);
            $product->setNom(json_decode($request->getContent())->nom);
            $product->setDescription(json_decode($request->getContent())->description);
            $product->setNbPiece(json_decode($request->getContent())->nb_piece);
            $product->setAge(json_decode($request->getContent())->age);
            $product->setDimension(json_decode($request->getContent())->dimension);
            $product->setPrix(json_decode($request->getContent())->prix);
            $product->setPetiteDesc(json_decode($request->getContent())->petite_desc);
            $product->setImg(json_decode($request->getContent())->img);
            $product->setStock(json_decode($request->getContent())->stock);
            $product->setNouveau(true);
            $product->setPopularite(0);
            $entityManager->persist($product);
            $entityManager->flush();


            return $this->json([
                "Rôle présent et produit ajouté",
                $product
            ]);
        }

        return $this->json([
            "Vous n'avez pas les rôles pour faire ça."
        ]);
    }

    #[Route('/produit', name: 'app_product_del', methods: ['DELETE'])]
    public function delete(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $request = json_decode($request->getContent());

        $product = $entityManager->getRepository(Produit::class)->find($request->id_product);
        $role = $entityManager->getRepository(User::class)->find($request->id_user)->getRoles();

        if (in_array('ROLE_ADMIN', $role)) {
            $entityManager->remove($product);
            $entityManager->flush();
            return $this->json([
                'Rôle présent et produit supprimé',
                $product
            ]);
        }

        return $this->json([
            "Vous n'avez pas les rôles pour faire ça."
        ]);
    }
    #[Route('/produit', name: 'app_product_update', methods: ['PATCH'])]
    public function patch(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $request = json_decode($request->getContent());

        $product = $entityManager->getRepository(Produit::class)->find($request->id_product);
        $role = $entityManager->getRepository(User::class)->find($request->id_user)->getRoles();

        if (in_array('ROLE_ADMIN', $role)) {
            $product->setAge($request->age);
            $product->setDescription($request->description);
            $product->setPetiteDesc($request->petite_desc);
            $product->setDimension($request->dimension);
            $product->setNbPiece($request->nb_piece);
            $product->setNom($request->nom);
            $product->setImg($request->img);
            $product->setPrix($request->prix);
            $product->setIdTheme($request->id_theme);
            $product->setStock($request->stock);
            $product->setPopularite($request->popularite);
            $product->setNouveau($request->nouveau);
            
            if($request->reduction > 0)$product->setReduction($request->reduction);
            else $product->setReduction(null);


            $entityManager->flush();

            return $this->json(['Produit modifié !']);
        }

        return $this->json(["Vous n'avez pas les rôles pour faire ça."]);
    }
}
