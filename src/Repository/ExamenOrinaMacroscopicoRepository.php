<?php

namespace App\Repository;

use App\Entity\ExamenOrinaMacroscopico;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ExamenOrinaMacroscopico|null find($id, $lockMode = null, $lockVersion = null)
 * @method ExamenOrinaMacroscopico|null findOneBy(array $criteria, array $orderBy = null)
 * @method ExamenOrinaMacroscopico[]    findAll()
 * @method ExamenOrinaMacroscopico[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExamenOrinaMacroscopicoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ExamenOrinaMacroscopico::class);
    }

    // /**
    //  * @return ExamenOrinaMacroscopico[] Returns an array of ExamenOrinaMacroscopico objects
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
    public function findOneBySomeField($value): ?ExamenOrinaMacroscopico
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
