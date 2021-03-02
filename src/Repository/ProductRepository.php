<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }


    /**
      * Return all Product per page
     */
    public function getPaginatedProductQuery(): \Doctrine\ORM\Query
    {
        $query = $this->createQueryBuilder('p');
        return $query->getQuery();
    }


/*    public function getPaginatedProduct($page, $limit){

        $query = $this->createQueryBuilder('p')
            ->setFirstResult(($page* $limit)-$limit)
            ->setMaxResults($limit)
        ;
        return $query->getQuery()->getResult();
    }*/

    /**
     * Return number of product
     */
    public function getTotalProduct(){
        $query = $this->createQueryBuilder('p')
            ->select('COUNT(p)');
            return (int)$query->getQuery()->getSingleScalarResult();
    }


    // /**
    //  * @return Product[] Returns an array of Product objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Product
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
