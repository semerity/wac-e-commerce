<?php

namespace App\Controller;

use App\Entity\Pays;
use App\Entity\Produit;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/*
    Okay, donc j'ai check pour plusieur api diff et pour le moment je suis tomber sur shipengine, kario, Easy post et API Shippo.
     - shipengine est un service payant donc un peut relou mais ya une version gratuite qui dure seulement 31 jours et jsp pas trop si ya un version de test/dev pour eviter les pb.
     - kario à un service open source et il faut que je check plus pour voir comment utiliser mais ya une version test et pas de limite. Par contre il me faut un compte sur d'autre truc pour vraiment tester.
     - Easy post j'ai pas trop regarder
     - API Shippo j'ai pas trop regarder non plus

    faut que je check directement avec les boites de livraison (chronopost, DHL, UPS, ect.) pour voir si ils ont un trucs utilisables.
*/

function getProductInArray($idProductArray, EntityManagerInterface $entityManager) {
    $resultArray = [];
    $repository = $entityManager->getRepository(Produit::class);
    foreach ($idProductArray as $key => $value) {
        array_push($resultArray,$repository->getOneProduct($value));
    }
    return $resultArray;
}

class FraisPortController extends AbstractController
{
    #[Route('/frais/estimation', name: 'app_frais_port', methods: ['POST'])]
    public function estimation(Request $asked, EntityManagerInterface $entityManager): Response
    {
        $volumePiece = 2.4*1.6*1.2;
        $weightPiece = 0.88;
        $produitrepo = $entityManager->getRepository(Produit::class);
        $paysrepo = $entityManager->getRepository(Pays::class);
        $data =  json_decode($asked->getContent());
        // dd($data);
        if ($paysrepo->findOneByNom($data->nom)) {
            $pays = $paysrepo->findOneByNom($data->nom);
        } else {
            return $this->json(['error'=>'pays pas dans la liste'],400);
        }
        // dd($pays, $data);
        $reqArr = $data->products;
        // dd($reqArr);
        $resultArray = [];
        foreach ($reqArr as $key => $value) {
            array_push($resultArray,$produitrepo->getOneProductByName($value));
        }
        // dd($resultArray);
        $total = 0;
        foreach ($resultArray as $key => $value) {
            $total+=$value['nb_piece'];
        }
        $volumeTotal = intval(ceil(($total*$volumePiece)*1.2));
        $weightTotal = $total * $weightPiece;
        $str = $volumeTotal . " cm^3";
        // dd($total,intdiv($volumeTotal,15)/100,$weightTotal, $str);
        $frais = intdiv($volumeTotal,15)/100;
        if ($weightTotal < 5000) {
            $frais += 4;
        } else {
            $frais += intval(intval(ceil($weightTotal))/1000);
        }
        // dd($pays->getTarif());
        $frais += $pays->getTarif();
        if ($frais < 12){
            $frais += 8;
            $frais = ($frais<10) ? $frais + 2 : $frais ; 
            $frais = round($frais,2);
        }

        /**
         * honnètement jsp pas trop quoi faire mais maintenant qu'on peut faire notre propre truc, on a une idée simple qu'il faut que je setup
         * le plan est simple : chaque produit lego a un nombre de pieces particulier ainsi on vas creer/considerer une piece generique avec une L, l, h et un poid
         * nous permenttant de décider du prix du transport par rapport au nombre de piece total d'un colis particulier
         * Pour l'histoire de la distance, il faut que je check pour trouver un moyen d'avoir la distance entre notre soon to be decided entrepot/location et l'entrain de livraison
         */

        return $this->json($frais);
    }
}
