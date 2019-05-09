<?php

namespace App\Repository;

use App\Entity\ExamenHecesMacroscopico;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ExamenHecesMacroscopico|null find($id, $lockMode = null, $lockVersion = null)
 * @method ExamenHecesMacroscopico|null findOneBy(array $criteria, array $orderBy = null)
 * @method ExamenHecesMacroscopico[]    findAll()
 * @method ExamenHecesMacroscopico[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExamenHecesMacroscopicoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ExamenHecesMacroscopico::class);
    }

    // /**
    //  * @return ExamenHecesMacroscopico[] Returns an array of ExamenHecesMacroscopico objects
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
    public function findOneBySomeField($value): ?ExamenHecesMacroscopico
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
