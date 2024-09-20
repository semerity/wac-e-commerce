<?php

namespace App\Repository;

use App\Entity\Adresses;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Adresses>
 */
class AdressesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Adresses::class);
    }

    //    /**
    //     * @return Adresses[] Returns an array of Adresses objects
    //     */
       public function findByExampleField($value): array
       {
           return $this->createQueryBuilder('a')
               ->andWhere('a.id_user = :val')
               ->setParameter('val', $value)
               ->getQuery()
               ->getResult()
           ;
       }

       public function findOneBySomeField($value): ?Adresses
       {
           return $this->createQueryBuilder('a')
               ->andWhere('a.id = :val')
               ->setParameter('val', $value)
               ->getQuery()
               ->getOneOrNullResult()
           ;
       }
}
