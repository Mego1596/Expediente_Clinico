<?php

namespace App\Repository;

use App\Entity\PlanTratamiento;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PlanTratamiento|null find($id, $lockMode = null, $lockVersion = null)
 * @method PlanTratamiento|null findOneBy(array $criteria, array $orderBy = null)
 * @method PlanTratamiento[]    findAll()
 * @method PlanTratamiento[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlanTratamientoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PlanTratamiento::class);
    }

    // /**
    //  * @return PlanTratamiento[] Returns an array of PlanTratamiento objects
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
    public function findOneBySomeField($value): ?PlanTratamiento
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
