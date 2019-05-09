<?php

namespace App\Repository;

use App\Entity\HistorialFamiliar;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method HistorialFamiliar|null find($id, $lockMode = null, $lockVersion = null)
 * @method HistorialFamiliar|null findOneBy(array $criteria, array $orderBy = null)
 * @method HistorialFamiliar[]    findAll()
 * @method HistorialFamiliar[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HistorialFamiliarRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, HistorialFamiliar::class);
    }

    // /**
    //  * @return HistorialFamiliar[] Returns an array of HistorialFamiliar objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('h.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?HistorialFamiliar
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
