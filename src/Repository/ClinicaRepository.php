<?php

namespace App\Repository;

use App\Entity\Clinica;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Clinica|null find($id, $lockMode = null, $lockVersion = null)
 * @method Clinica|null findOneBy(array $criteria, array $orderBy = null)
 * @method Clinica[]    findAll()
 * @method Clinica[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClinicaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Clinica::class);
    }

    // /**
    //  * @return Clinica[] Returns an array of Clinica objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Clinica
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
