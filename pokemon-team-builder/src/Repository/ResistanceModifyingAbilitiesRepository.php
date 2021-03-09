<?php

namespace App\Repository;

use App\Entity\ResistanceModifyingAbility;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ResistanceModifyingAbility|null find($id, $lockMode = null, $lockVersion = null)
 * @method ResistanceModifyingAbility|null findOneBy(array $criteria, array $orderBy = null)
 * @method ResistanceModifyingAbility[]    findAll()
 * @method ResistanceModifyingAbility[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ResistanceModifyingAbilitiesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ResistanceModifyingAbility::class);
    }

    // /**
    //  * @return ResistanceModifyingAbility[] Returns an array of ResistanceModifyingAbility objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ResistanceModifyingAbility
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
