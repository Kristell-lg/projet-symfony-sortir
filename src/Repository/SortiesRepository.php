<?php

namespace App\Repository;

use App\Entity\Sorties;
use App\Entity\SortieSearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Sorties|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sorties|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sorties[]    findAll()
 * @method Sorties[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortiesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sorties::class);
    }

    // /**
    //  * @return Sorties[] Returns an array of Sorties objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Sorties
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function findWanted(SortieSearch $pSearch)
    {
        $queryBuilder = $this->createQueryBuilder('s');

        $campus = $pSearch->getCampus()->getId();

        $queryBuilder->andWhere('s.campus = :campus')
        ->setParameter('campus', $campus );

        if ($pSearch->getRecherche() !=null ) {
            $search = $pSearch->getRecherche();
            $queryBuilder->andWhere('s.nom LIKE :search')
                ->setParameter('search', '%' . $search . '%');
        }
        if($pSearch->getApresLe()!=null){
            $apresLe = $pSearch->getApresLe();
            $queryBuilder->andWhere('s.dateHeureDebut >= :apresLe')
                ->setParameter('apresLe',$apresLe);
        }
        if($pSearch->getAvantLe()!=null){
            $avantLe = $pSearch->getAvantLe();
            $queryBuilder->andWhere('s.dateHeureDebut <= :avantLe')
                ->setParameter('avantLe',$avantLe);
        }

        $query = $queryBuilder->getQuery();
        return $query->getResult();
    }
}