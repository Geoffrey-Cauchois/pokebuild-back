<?php

namespace App\Repository;

use App\Entity\TeamAppartenance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TeamAppartenance|null find($id, $lockMode = null, $lockVersion = null)
 * @method TeamAppartenance|null findOneBy(array $criteria, array $orderBy = null)
 * @method TeamAppartenance[]    findAll()
 * @method TeamAppartenance[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TeamAppartenanceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TeamAppartenance::class);
    }

    // /**
    //  * @return TeamAppartenance[] Returns an array of TeamAppartenance objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TeamAppartenance
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
