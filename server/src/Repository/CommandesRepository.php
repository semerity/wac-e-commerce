<?php

namespace App\Repository;

use App\Entity\Commandes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query;

/**
 * @extends ServiceEntityRepository<Commandes>
 */
class CommandesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Commandes::class);
    }

    //    /**
//     * @return Commandes[] Returns an array of Commandes objects
//     */
   public function findByIdUser($value): array
   {
       return $this->createQueryBuilder('c')
           ->andWhere('c.id_user = :val')
           ->setParameter('val', $value)
           ->orderBy('c.id', 'ASC')
           ->getQuery()
           ->getResult()
       ;
   }

    public function findByUserId($id)
    {
        return $this->createQueryBuilder('p')
            ->select('p')
            ->andWhere('p.id_user = :val')
            ->setParameter('val', $id)
            ->getQuery(Query::HYDRATE_ARRAY)
            ->getArrayResult();
    }
    //    public function findOneBySomeField($value): ?Commandes
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
