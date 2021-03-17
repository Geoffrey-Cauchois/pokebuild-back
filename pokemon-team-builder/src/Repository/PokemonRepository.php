<?php

namespace App\Repository;

use App\Entity\Pokemon;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Pokemon|null find($id, $lockMode = null, $lockVersion = null)
 * @method Pokemon|null findOneBy(array $criteria, array $orderBy = null)
 * @method Pokemon[]    findAll()
 * @method Pokemon[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PokemonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pokemon::class);
    }

    public function findByType($typeName)
    {
        // query to find the pokemons which have the type of (value of $type)
        return $this->createQueryBuilder('p')
            ->select('p')
            ->innerJoin('p.types', 't')
            ->andWhere('t.name = :type')
            ->setParameter('type', $typeName)
            ->getQuery()
            ->getResult();
   
        
    }

    public function  findByLimit($number) 
    {
        // query to find the (value of $number) first pokemons
        return $this->createQueryBuilder('p')
            ->orderBy('p.id', 'ASC')
            ->setMaxResults($number)
            ->getQuery()
            ->getResult();
    }    


    public function findByTypes($type1, $type2)
    {
        // query to find the pokemons which have the types of  (value of $type1 and $type2)
        return $this->createQueryBuilder('p')
                  ->select('p')
                  ->innerJoin('p.types', 't1')
                  ->innerJoin('p.types', 't2')
                  ->andWhere('t1.name = :type1')
                  ->andWhere('t2.name = :type2')
                  ->setParameter('type1', $type1)
                  ->setParameter('type2', $type2)
                  ->getQuery()
                  ->getResult();
    }




    // /**
    //  * @return Pokemon[] Returns an array of Pokemon objects
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
    public function findOneBySomeField($value): ?Pokemon
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
