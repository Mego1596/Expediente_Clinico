<?php

namespace App\Repository;

use App\Entity\ExamenOrinaCristaluria;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ExamenOrinaCristaluria|null find($id, $lockMode = null, $lockVersion = null)
 * @method ExamenOrinaCristaluria|null findOneBy(array $criteria, array $orderBy = null)
 * @method ExamenOrinaCristaluria[]    findAll()
 * @method ExamenOrinaCristaluria[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExamenOrinaCristaluriaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ExamenOrinaCristaluria::class);
    }

    // /**
    //  * @return ExamenOrinaCristaluria[] Returns an array of ExamenOrinaCristaluria objects
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
    public function findOneBySomeField($value): ?ExamenOrinaCristaluria
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
