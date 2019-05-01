<?php

namespace App\Repository;

use App\Entity\ExamenHecesMicroscopico;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ExamenHecesMicroscopico|null find($id, $lockMode = null, $lockVersion = null)
 * @method ExamenHecesMicroscopico|null findOneBy(array $criteria, array $orderBy = null)
 * @method ExamenHecesMicroscopico[]    findAll()
 * @method ExamenHecesMicroscopico[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExamenHecesMicroscopicoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ExamenHecesMicroscopico::class);
    }

    // /**
    //  * @return ExamenHecesMicroscopico[] Returns an array of ExamenHecesMicroscopico objects
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
    public function findOneBySomeField($value): ?ExamenHecesMicroscopico
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
