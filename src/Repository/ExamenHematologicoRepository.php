<?php

namespace App\Repository;

use App\Entity\ExamenHematologico;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ExamenHematologico|null find($id, $lockMode = null, $lockVersion = null)
 * @method ExamenHematologico|null findOneBy(array $criteria, array $orderBy = null)
 * @method ExamenHematologico[]    findAll()
 * @method ExamenHematologico[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExamenHematologicoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ExamenHematologico::class);
    }

    // /**
    //  * @return ExamenHematologico[] Returns an array of ExamenHematologico objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ExamenHematologico
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
