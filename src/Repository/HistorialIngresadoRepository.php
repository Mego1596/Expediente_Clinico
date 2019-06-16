<?php

namespace App\Repository;

use App\Entity\HistorialIngresado;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method HistorialIngresado|null find($id, $lockMode = null, $lockVersion = null)
 * @method HistorialIngresado|null findOneBy(array $criteria, array $orderBy = null)
 * @method HistorialIngresado[]    findAll()
 * @method HistorialIngresado[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HistorialIngresadoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, HistorialIngresado::class);
    }

    // /**
    //  * @return HistorialIngresado[] Returns an array of HistorialIngresado objects
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
    public function findOneBySomeField($value): ?HistorialIngresado
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
