<?php

namespace App\Repository;

use App\Entity\Panier;
use App\Entity\Produit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query;


/**
 * @extends ServiceEntityRepository<Panier>
 */
class PanierRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Panier::class);
    }

    public function getOneCart($id)
    {
        return $this->createQueryBuilder('panier')
            ->select('panier')
            ->where('panier.id_user = :id')
            ->setParameter(':id', $id)
            ->getQuery(Query::HYDRATE_ARRAY)
            ->getArrayResult();
    }

    public function deleteOneProduct($id)
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = '
        SELECT id
        FROM panier
        WHERE id = :id
    ';
        $stmt = $conn->prepare($sql);
        $result = $stmt->executeQuery(['id' => $id])->fetchAssociative();

        if ($result) {
            $idToDelete = $result['id'];
            return $this->createQueryBuilder('panier')
                ->delete()
                ->where('panier.id = :id')
                ->setParameter(':id', $idToDelete)
                ->getQuery()
                ->execute();
        }

        return 0;
    }
    public function deleteUser($id)
    {
        $this->createQueryBuilder('panier')
            ->delete()
            ->where('panier.id_user = :id')
            ->setParameter(':id', $id)
            ->getQuery()
            ->execute();
    }

    public function findProduitsByUserId(int $userId)
    {
        return $this->createQueryBuilder('pan')
            ->innerJoin('App\Entity\Produit', 'p', 'WITH', 'pan.id_product = p.id')
            ->where('pan.id_user = :userId')
            ->setParameter('userId', $userId)
            ->select('p')
            ->getQuery(Query::HYDRATE_ARRAY)
            ->getArrayResult();
    }

    //    /**
    //     * @return Panier[] Returns an array of Panier objects
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

    //    public function findOneBySomeField($value): ?Panier
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
