<?php

namespace App\Repository;

use App\Entity\Participant;
use App\Entity\Sorties;
use App\Entity\SortieSearch;
use DateInterval;
use DateTime;
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
    public function findWanted(SortieSearch $pSearch,Participant $pParticipant)
    {
        $date = new DateTime('now');
        $queryBuilder = $this->createQueryBuilder('s');
        $queryBuilder->leftJoin('s.sortieParticipants','ps');

        if ($pSearch->getOrganisateur()){
            $organisateur = $pParticipant->getId();
            $queryBuilder->andWhere('s.organisateur = :organisateur')
                ->setParameter('organisateur',$organisateur);
        }
        if ($pSearch->getInscrit()){
            $participant = $pParticipant->getId();
            if($pSearch->getOrganisateur()||$pSearch->getNoInscrit()||$pSearch->getPassees()){
                $queryBuilder->orWhere('ps.id = :participant');
            }else{
                $queryBuilder->andWhere('ps.id = :participant');
            }
            $queryBuilder->setParameter('participant',$participant);
        }
        if ($pSearch->getNoInscrit()){
            $participant = $pParticipant->getId();

            if($pSearch->getOrganisateur()||$pSearch->getInscrit()||$pSearch->getPassees()){
                $queryBuilder->orWhere('ps.id != (:participant) OR ps.id is null');
            }else{
                $queryBuilder->andWhere('ps.id != (:participant) OR ps.id is null');
            }
            $queryBuilder->setParameter('participant',$participant);
        }

        if ($pSearch->getPassees()){
            if($pSearch->getOrganisateur()||$pSearch->getInscrit()||$pSearch->getNoInscrit()){
                $queryBuilder->orWhere('s.dateHeureDebut < :date');
            }else{
                $queryBuilder->andWhere('s.dateHeureDebut <:date');
            }
            $queryBuilder->setParameter('date',$date);
        }
        if($pSearch->getCampus()!=null) {
            $campus = $pSearch->getCampus()->getId();
            $queryBuilder->andWhere('s.campus = :campus')
                ->setParameter('campus', $campus);
        }
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
        $queryBuilder->andWhere('s.dateHeureDebut >:date');
        $dateM = $date;
        $dateM->sub(new DateInterval('P1M'));
        $queryBuilder->setParameter('date',$dateM);

        $query = $queryBuilder->getQuery();
        return $query->getResult();
    }
}