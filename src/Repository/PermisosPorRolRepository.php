<?php

namespace App\Repository;

use App\Entity\PermisosPorRol;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PermisosPorRol|null find($id, $lockMode = null, $lockVersion = null)
 * @method PermisosPorRol|null findOneBy(array $criteria, array $orderBy = null)
 * @method PermisosPorRol[]    findAll()
 * @method PermisosPorRol[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PermisosPorRolRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PermisosPorRol::class);
    }

    // /**
    //  * @return PermisosPorRol[] Returns an array of PermisosPorRol objects
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
    public function findOneBySomeField($value): ?PermisosPorRol
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
