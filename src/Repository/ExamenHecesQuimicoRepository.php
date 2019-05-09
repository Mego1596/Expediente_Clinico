<?php

namespace App\Repository;

use App\Entity\ExamenHecesQuimico;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ExamenHecesQuimico|null find($id, $lockMode = null, $lockVersion = null)
 * @method ExamenHecesQuimico|null findOneBy(array $criteria, array $orderBy = null)
 * @method ExamenHecesQuimico[]    findAll()
 * @method ExamenHecesQuimico[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExamenHecesQuimicoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ExamenHecesQuimico::class);
    }

    // /**
    //  * @return ExamenHecesQuimico[] Returns an array of ExamenHecesQuimico objects
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
    public function findOneBySomeField($value): ?ExamenHecesQuimico
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
