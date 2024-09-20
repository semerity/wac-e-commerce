<?php

namespace App\Controller;

use App\Entity\Adresses;
use App\Entity\Avis;
use App\Entity\Commandes;
use App\Entity\DeliveryMethod;
use App\Entity\Evenement;
use App\Entity\Paiement;
use App\Entity\Panier;
use App\Entity\Pays;
use App\Entity\Produit;
use App\Entity\Promotion;
use App\Entity\Theme;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ReportController extends AbstractController
{
    #[Route('/report', name: 'app_report')]
    public function index(EntityManagerInterface $em): Response
    {
        $users = $em->getRepository(User::class)->findAll();
        $commandes = $em->getRepository(Commandes::class)->findAll();
        // $adresses = $em->getRepository(Adresses::class)->findAll();
        // $avis = $em->getRepository(Avis::class)->findAll();
        // $deliverymethods = $em->getRepository(DeliveryMethod::class)->findAll();
        // $evenements = $em->getRepository(Evenement::class)->findAll();
        // $paiements = $em->getRepository(Paiement::class)->findAll();
        // $paniers = $em->getRepository(Panier::class)->findAll();
        // $pays = $em->getRepository(Pays::class)->findAll();
        $produits = $em->getRepository(Produit::class)->findAll();
        // $promotions = $em->getRepository(Promotion::class)->findAll();
        // $themes = $em->getRepository(Theme::class)->findAll();

        $rows = array(['Utitlisateur']);
        array_push($rows,["id", "email", "roles", "commmandes"]);
        foreach ($users as $user) {
            $data = array($user->getId(),$user->getEmail());

            $roles = implode(',', $user->getRoles());
            $commands = $em->getRepository(Commandes::class)->findByIdUser($user);
            $arrCom = [];
            foreach ($commands as $command) {
                array_push($arrCom, $command->getId());
            }
            // dd($arrCom);
            array_push($data,$roles);
            array_push($data,implode(",",$arrCom));

            $rows[] = $data;
        }
        // dd($rows);
        
        array_push($rows,[]);
        array_push($rows,['commandes']);
        array_push($rows,['id','adresse','prix','articles','etat','id_user','method','embal']);
        foreach ($commandes as $commande) {
            // $data = array($commande->getId(),$commande->getAdresse(),$commande->getPrix(),$commande->getArticles(),$commande->getEtat(),$commande->getIdUser(),$commande->getMethod(),$commande->isEmbal());
            $data = (array) $commande;

            $rows[] = $data;
        }
        // array_push($rows,[]);
        // array_push($rows,['adresses']);
        // array_push($rows,['id','id_user','pays','ville','code_postal','nom_de_rue']);
        // foreach ($adresses as $adresse) {
        //     $data = array($adresse->getId(),$adresse->getIdUser(),$adresse->getPays(),$adresse->getVille(),$adresse->getCodePostal(),$adresse->getNomDeRue());

        //     $rows[] = $data;
        // }
        // array_push($rows,[]);
        // array_push($rows,['avis']);
        // array_push($rows,['id','id_product','note','content','id_user']);
        // foreach ($avis as $avi) {
        //     $data = (array) $avi;

        //     $rows[] = $data;
        // }
        // array_push($rows,[]);
        // array_push($rows,['delivery method']);
        // array_push($rows,['id','name','mult']);
        // foreach ($deliverymethods as $method) {
        //     $data = (array) $method;

        //     $rows[] = $data;
        // }
        // array_push($rows,[]);
        // array_push($rows,['evenement']);
        // array_push($rows,['id','date debut','date fin','nom']);
        // foreach ($evenements as $evenement) {
        //     $data = (array) $evenement;

        //     $rows[] = $data;
        // }
        // array_push($rows,[]);
        // array_push($rows,['paiement']);
        // array_push($rows,['id','id user','name','code','date','cryptogramme']);
        // foreach ($paiements as $paiement) {
        //     $data = (array) $paiement;

        //     $rows[] = $data;
        // }
        // array_push($rows,[]);
        // array_push($rows,['panier']);
        // array_push($rows,['id','id user','id product']);
        // foreach ($paniers as $panier) {
        //     $data = (array) $panier;

        //     $rows[] = $data;
        // }
        // array_push($rows,[]);
        // array_push($rows,['pays']);
        // array_push($rows,['id','nom','tarif']);
        // foreach ($pays as $p) {
        //     $data = (array) $p;

        //     $rows[] = $data;
        // }
        // array_push($rows,[]);
        // array_push($rows,['produit']);
        // array_push($rows,['id','id theme','nom','description','nb piece','age','dimension','prix','petite desc','img','popularité','stock','reduction','nouveau']);
        // foreach ($produits as $produit) {
        //     $data = (array) $produit;

        //     $rows[] = $data;
        // }
        // array_push($rows,[]);
        // array_push($rows,['promotion']);
        // array_push($rows,['id','id product','promotion','is discounted']);
        // foreach ($promotions as $promo) {
        //     $data = (array) $promo;

        //     $rows[] = $data;
        // }
        // array_push($rows,[]);
        // array_push($rows,['theme']);
        // array_push($rows,['id','theme','color']);
        // foreach ($themes as $theme) {
        //     $data = (array) $theme;

        //     $rows[] = $data;
        // }


        $fp = fopen('temp', 'wb');
        foreach ($rows as $row) {
            fputcsv($fp,$row);
        }
        fclose($fp);
        
        $response = new Response(file_get_contents('temp'));
        $response->headers->set('Content-Type','text/csv');

        return $response;
    }
}
