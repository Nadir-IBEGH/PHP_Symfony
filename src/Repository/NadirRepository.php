<?php

namespace App\Repository;

use App\Entity\Nadir;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Nadir|null find($id, $lockMode = null, $lockVersion = null)
 * @method Nadir|null findOneBy(array $criteria, array $orderBy = null)
 * @method Nadir[]    findAll()
 * @method Nadir[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NadirRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Nadir::class);
    }

    // /**
    //  * @return Nadir[] Returns an array of Nadir objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('n.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Nadir
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
