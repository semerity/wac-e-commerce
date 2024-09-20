<?php

namespace App\Repository;

use App\Entity\Produit;
use App\Entity\Theme;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query;

/**
 * @extends ServiceEntityRepository<Produit>
 */
class ProduitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Produit::class);
    }

    public function all()
    {
        return $this->findAll();
    }

    public function getAllProducts()
    {
        return $this->createQueryBuilder("p")
            ->orderBy("p.popularite", 'DESC')
            ->getQuery(Query::HYDRATE_ARRAY)
            ->getArrayResult();
    }

    public function getOneProduct($id)
    {
        return $this->createQueryBuilder('produit')
            ->select('produit')
            ->where('produit.id = :id')
            ->setParameter(':id', $id)
            ->getQuery(Query::HYDRATE_ARRAY)
            ->getArrayResult();
    }

    public function getOneProductByName($name)
    {
        return $this->createQueryBuilder('produit')
            ->select('produit')
            ->where('produit.nom = :id')
            ->setParameter(':id', $name)
            ->getQuery(Query::HYDRATE_ARRAY)
            ->getArrayResult()[0];
    }

    //    /**
    //     * @return Produit[] Returns an array of Produit objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Produit
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
