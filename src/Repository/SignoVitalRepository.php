<?php

namespace App\Repository;

use App\Entity\SignoVital;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method SignoVital|null find($id, $lockMode = null, $lockVersion = null)
 * @method SignoVital|null findOneBy(array $criteria, array $orderBy = null)
 * @method SignoVital[]    findAll()
 * @method SignoVital[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SignoVitalRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, SignoVital::class);
    }

    // /**
    //  * @return SignoVital[] Returns an array of SignoVital objects
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
    public function findOneBySomeField($value): ?SignoVital
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
