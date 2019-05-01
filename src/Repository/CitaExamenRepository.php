<?php

namespace App\Repository;

use App\Entity\CitaExamen;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CitaExamen|null find($id, $lockMode = null, $lockVersion = null)
 * @method CitaExamen|null findOneBy(array $criteria, array $orderBy = null)
 * @method CitaExamen[]    findAll()
 * @method CitaExamen[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CitaExamenRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CitaExamen::class);
    }

    // /**
    //  * @return CitaExamen[] Returns an array of CitaExamen objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CitaExamen
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
