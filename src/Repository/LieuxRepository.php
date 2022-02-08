<?php

namespace App\Repository;

use App\Entity\Lieux;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Lieux|null find($id, $lockMode = null, $lockVersion = null)
 * @method Lieux|null findOneBy(array $criteria, array $orderBy = null)
 * @method Lieux[]    findAll()
 * @method Lieux[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LieuxRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Lieux::class);
    }

    // /**
    //  * @return Lieux[] Returns an array of Lieux objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Lieux
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
