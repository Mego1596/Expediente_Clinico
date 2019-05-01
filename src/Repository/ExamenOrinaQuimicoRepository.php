<?php

namespace App\Repository;

use App\Entity\ExamenOrinaQuimico;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ExamenOrinaQuimico|null find($id, $lockMode = null, $lockVersion = null)
 * @method ExamenOrinaQuimico|null findOneBy(array $criteria, array $orderBy = null)
 * @method ExamenOrinaQuimico[]    findAll()
 * @method ExamenOrinaQuimico[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExamenOrinaQuimicoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ExamenOrinaQuimico::class);
    }

    // /**
    //  * @return ExamenOrinaQuimico[] Returns an array of ExamenOrinaQuimico objects
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
    public function findOneBySomeField($value): ?ExamenOrinaQuimico
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
