<?php

namespace App\Repository;

use App\Entity\HistorialPropio;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method HistorialPropio|null find($id, $lockMode = null, $lockVersion = null)
 * @method HistorialPropio|null findOneBy(array $criteria, array $orderBy = null)
 * @method HistorialPropio[]    findAll()
 * @method HistorialPropio[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HistorialPropioRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, HistorialPropio::class);
    }

    // /**
    //  * @return HistorialPropio[] Returns an array of HistorialPropio objects
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
    public function findOneBySomeField($value): ?HistorialPropio
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
