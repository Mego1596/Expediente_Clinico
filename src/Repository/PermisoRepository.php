<?php

namespace App\Repository;

use App\Entity\Permiso;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Permiso|null find($id, $lockMode = null, $lockVersion = null)
 * @method Permiso|null findOneBy(array $criteria, array $orderBy = null)
 * @method Permiso[]    findAll()
 * @method Permiso[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PermisoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Permiso::class);
    }

    // /**
    //  * @return Permiso[] Returns an array of Permiso objects
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
    public function findOneBySomeField($value): ?Permiso
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
