<?php

namespace App\Repository;

use App\Entity\HistoriaMedica;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method HistoriaMedica|null find($id, $lockMode = null, $lockVersion = null)
 * @method HistoriaMedica|null findOneBy(array $criteria, array $orderBy = null)
 * @method HistoriaMedica[]    findAll()
 * @method HistoriaMedica[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HistoriaMedicaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, HistoriaMedica::class);
    }

    // /**
    //  * @return HistoriaMedica[] Returns an array of HistoriaMedica objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('h.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?HistoriaMedica
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
