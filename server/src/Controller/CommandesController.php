<?php

namespace App\Controller;

use App\Entity\Commandes;
use App\Entity\Pays;
use App\Entity\Produit;
use App\Entity\DeliveryMethod;
use App\Form\CommandesType;
use App\Repository\CommandesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[Route('/commandes')]
class CommandesController extends AbstractController
{
    #[Route('/', name: 'app_commandes_index', methods: ['GET'])]
    public function index(CommandesRepository $commandesRepository): Response
    {
        return $this->json([
            'commandes' => $commandesRepository->findAll(),
        ], 200);
    }

    #[Route('/new', name: 'app_commandes_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, HttpClientInterface $client): Response
    {
        $volumePiece = 2.4 * 1.6 * 1.2;
        $weightPiece = 0.88;
        $produitrepo = $entityManager->getRepository(Produit::class);
        $paysrepo = $entityManager->getRepository(Pays::class);
        $DeliveryMethod = $entityManager->getRepository(DeliveryMethod::class);
        $data = json_decode($request->getContent());
        $dataArticles = json_decode($data->articles);
        // dd($dataArticles);
        $totalPrix = 0;
        $totalPiece = 0;
        if ($paysrepo->findOneByNom($data->pays)) {
            $pays = $paysrepo->findOneByNom($data->pays);
        } else {
            return $this->json(['error' => 'pays pas dans la liste'], 400);
        }
        $tabArticle = [];
        foreach ($dataArticles as $key => $value) {
            for ($i = 0; $i < intval($value->nb); $i++) {
                array_push($tabArticle, $produitrepo->getOneProductByName($value->name));
            }
            // $totalPrix += $produitrepo->find($value)->getPrix();
            if (isset($value->reduction)) {
                $totalPrix += ($value->price - $value->reduction) * intval($value->nb);
            } else {
                $totalPrix += $value->price * intval($value->nb);
            }
        }
        foreach ($tabArticle as $key => $value) {
            // dd($value[0]['nb_piece']);
            $totalPiece += $value['nb_piece'];
        }
        $volumeTotal = intval(ceil(($totalPiece * $volumePiece) * 1.2));
        $weightTotal = $totalPiece * $weightPiece;
        $frais = intdiv($volumeTotal, 15) / 100;
        if ($weightTotal < 5000) {
            $frais += 4;
        } else {
            $frais += intval(intval(ceil($weightTotal)) / 1000);
        }
        // dd($pays->getTarif());
        $frais += $pays->getTarif();
        if ($frais < 12) {
            $frais += 8;
            $frais = ($frais < 10) ? $frais + 2 : $frais;
            $frais = round($frais, 2);
        }
        $frais *= $DeliveryMethod->findOneById($data->method)->getMult();
        ;
        $totalPrix += $frais;
        // dd($total);
        $commande = new Commandes();
        $commande->setAdresse($data->adresse . " " . $data->pays);
        $commande->setPrix($totalPrix);
        $commande->setArticles($data->articles);
        $commande->setEtat('preparation');
        $commande->setMethod($data->method);
        $commande->setEmbal($data->embal);
        if (isset($data->id_user)) {
            $commande->setIdUser($data->id_user);
        }

        $entityManager->persist($commande);
        $entityManager->flush();

        return $this->json([
            'commande' => $commande
        ]);
    }

    #[Route('/{id}', name: 'app_commandes_show', methods: ['GET'])]
    public function show(Commandes $commande): Response
    {
        return $this->json([
            'commande' => $commande,
        ]);
    }
    #[Route('/user/{id}', name: 'app_commandes_user_show', methods: ['GET'])]
    public function showUser(CommandesRepository $commandesRepository, $id): Response
    {
        return $this->json($commandesRepository->findByUserId($id));
    }
    #[Route('/{id}', name: 'app_commandes_patch', methods: ['PATCH'])]
    public function commandPatch(Request $request, EntityManagerInterface $entityManager, Commandes $commande): Response
    {
        $r = json_decode($request->getContent());
        $commande->setAdresse($r->adresse);
        $commande->setEtat($r->etat);
        $commande->setPrix($r->prix);
        $commande->setMethod($r->method);
        $entityManager->persist($commande);
        $entityManager->flush();
        return $this->json('ok');
    }


    // #[Route('/{id}/edit', name: 'app_commandes_edit', methods: ['GET', 'POST'])]
    // public function edit(Request $request, Commandes $commande, EntityManagerInterface $entityManager): Response
    // {
    //     $form = $this->createForm(CommandesType::class, $commande);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $entityManager->flush();

    //         return $this->redirectToRoute('app_commandes_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->render('commandes/edit.html.twig', [
    //         'commande' => $commande,
    //         'form' => $form,
    //     ]);
    // }

    #[Route('/{id}', name: 'app_commandes_delete', methods: ['DELETE'])]
    public function delete(Request $request, Commandes $commande, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($commande);
        $entityManager->flush();

        return $this->json('deleted');
    }
}
